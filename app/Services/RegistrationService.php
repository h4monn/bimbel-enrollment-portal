<?php

namespace App\Services;

use App\Models\ClassRoom;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class RegistrationService
{
    public function __construct(
        protected MidtransService $midtransService,
    ) {}

    /**
     * Membuat pendaftaran, melakukan reservasi kuota secara atomic (lockForUpdate),
     * dan membuat transaksi Midtrans Snap dengan batas waktu 24 jam.
     *
     * @param  array<string, mixed>  $data
     * @return array{registration: Registration, payment: Payment, snap_token: string, redirect_url: string}
     */
    public function registerAndCreatePayment(User $user, array $data): array
    {
        /** @var array{registration: Registration, payment: Payment} $result */
        $result = DB::transaction(function () use ($user, $data): array {
            // Kunci baris kelas untuk mencegah race condition / overbooking
            $classRoom = ClassRoom::with('program')
                ->whereKey($data['class_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if (! $classRoom->is_active || ! $classRoom->program?->is_active) {
                throw ValidationException::withMessages([
                    'class_id' => 'Program atau kelas yang dipilih sudah tidak aktif.',
                ]);
            }

            if ($classRoom->enrolled_count >= $classRoom->quota) {
                throw ValidationException::withMessages([
                    'class_id' => 'Maaf, kuota kelas ini sudah penuh.',
                ]);
            }

            // Perbarui nomor WhatsApp wali/pendaftar jika berubah atau belum terisi
            if ($user->whatsapp !== $data['whatsapp']) {
                $user->update(['whatsapp' => $data['whatsapp']]);
            }

            $student = Student::create([
                'user_id' => $user->id,
                'full_name' => $data['student_full_name'],
                'birth_date' => $data['birth_date'],
                'school_name' => $data['school_name'],
                'education_level' => $data['education_level'],
                'grade' => $data['grade'],
            ]);

            // Reservasi kuota kelas secara atomic
            $classRoom->increment('enrolled_count');

            $totalAmount = (int) $classRoom->program->price;
            $now = now();
            $registrationNumber = $this->generateUniqueRegistrationNumber();

            $registration = Registration::create([
                'registration_number' => $registrationNumber,
                'user_id' => $user->id,
                'student_id' => $student->id,
                'class_id' => $classRoom->id,
                'total_amount' => $totalAmount,
                'status' => 'pending_payment',
                'agreed_terms_at' => $now,
                'expired_at' => $now->copy()->addHours(24),
            ]);

            $payment = Payment::create([
                'registration_id' => $registration->id,
                'order_id' => 'PAY-'.$registrationNumber.'-'.Str::upper(Str::random(4)),
                'amount' => $totalAmount,
                'status' => 'pending',
            ]);

            return [
                'registration' => $registration,
                'payment' => $payment,
            ];
        });

        try {
            $snap = $this->midtransService->createSnapTransaction(
                $result['registration'],
                $result['payment'],
                $user->fresh()
            );
        } catch (Throwable $e) {
            // Kompensasi: batalkan pendaftaran & kembalikan kuota jika pembuatan Snap gagal
            $this->releaseCancelledRegistration($result['registration'], 'failed');

            throw $e;
        }

        return [
            'registration' => $result['registration'],
            'payment' => $result['payment'],
            'snap_token' => $snap['snap_token'],
            'redirect_url' => $snap['redirect_url'],
        ];
    }

    /**
     * Mengembalikan kuota kelas secara atomic ketika pendaftaran dibatalkan / kedaluwarsa.
     */
    public function releaseCancelledRegistration(Registration $registration, string $paymentStatus = 'expired'): void
    {
        DB::transaction(function () use ($registration, $paymentStatus): void {
            $lockedReg = Registration::whereKey($registration->id)->lockForUpdate()->first();

            if (! $lockedReg || $lockedReg->status !== 'pending_payment') {
                return;
            }

            $lockedReg->update(['status' => 'cancelled']);

            $lockedReg->payments()
                ->where('status', 'pending')
                ->update(['status' => $paymentStatus]);

            $classRoom = ClassRoom::whereKey($lockedReg->class_id)->lockForUpdate()->first();
            if ($classRoom && $classRoom->enrolled_count > 0) {
                $classRoom->decrement('enrolled_count');
            }
        });
    }

    protected function generateUniqueRegistrationNumber(): string
    {
        $prefix = 'REG-'.now()->format('Ym').'-';

        do {
            $number = $prefix.Str::upper(Str::random(6));
        } while (Registration::where('registration_number', $number)->exists());

        return $number;
    }
}
