<?php

namespace App\Jobs;

use App\Models\Registration;
use App\Services\FonnteService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendRegistrationConfirmedWhatsApp implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public int $registrationId,
    ) {}

    public function handle(FonnteService $fonnteService): void
    {
        $registration = Registration::with(['user', 'student', 'classRoom.program'])
            ->find($this->registrationId);

        if (! $registration || ! $registration->user?->whatsapp) {
            return;
        }

        $totalFormatted = number_format($registration->total_amount, 0, ',', '.');
        $message = implode("\n", [
            '*KONFIRMASI PENDAFTARAN BIMBEL*',
            "Halo {$registration->user->name}, pembayaran Anda telah lunas dan pendaftaran telah *DIKONFIRMASI*.",
            '',
            "No. Pendaftaran: *{$registration->registration_number}*",
            "Nama Siswa: {$registration->student->full_name}",
            "Program: {$registration->classRoom->program->name}",
            "Kelas: {$registration->classRoom->name}",
            "Jadwal: {$registration->classRoom->schedule_days}, {$registration->classRoom->schedule_time} (Ruang {$registration->classRoom->room})",
            "Total Lunas: Rp {$totalFormatted}",
            '',
            'Terima kasih!',
        ]);

        $fonnteService->sendWhatsApp($registration->user->whatsapp, $message);
    }
}
