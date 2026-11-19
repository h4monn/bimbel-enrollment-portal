<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RegistrationsExport;
use App\Http\Controllers\Controller;
use App\Jobs\SendRegistrationConfirmedEmail;
use App\Jobs\SendRegistrationConfirmedWhatsApp;
use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'program_id', 'status', 'date_from', 'date_to']);

        $registrations = Registration::query()
            ->with(['user', 'student', 'classRoom.program', 'latestPayment'])
            ->when(! empty($filters['search']), function (Builder $query) use ($filters): void {
                $search = $filters['search'];
                $query->where(function (Builder $q) use ($search): void {
                    $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhereHas('student', fn (Builder $sq) => $sq->where('full_name', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn (Builder $uq) => $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('whatsapp', 'like', "%{$search}%"));
                });
            })
            ->when(! empty($filters['program_id']), function (Builder $query) use ($filters): void {
                $query->whereHas('classRoom', fn (Builder $cq) => $cq->where('program_id', $filters['program_id']));
            })
            ->when(! empty($filters['status']), function (Builder $query) use ($filters): void {
                $query->where('status', $filters['status']);
            })
            ->when(! empty($filters['date_from']), function (Builder $query) use ($filters): void {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            })
            ->when(! empty($filters['date_to']), function (Builder $query) use ($filters): void {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $programs = Program::orderBy('name')->get();

        return view('admin.registrations.index', [
            'registrations' => $registrations,
            'programs' => $programs,
            'filters' => $filters,
        ]);
    }

    public function updateStatus(Request $request, Registration $registration): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending_payment', 'paid', 'confirmed', 'cancelled', 'refunded'])],
            'refund_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $newStatus = $validated['status'];

        DB::transaction(function () use ($registration, $newStatus, $validated): void {
            $lockedReg = Registration::whereKey($registration->id)->lockForUpdate()->firstOrFail();
            $oldStatus = $lockedReg->status;

            $activeQuotaStatuses = ['pending_payment', 'paid', 'confirmed'];
            $wasOccupying = in_array($oldStatus, $activeQuotaStatuses, true);
            $willOccupy = in_array($newStatus, $activeQuotaStatuses, true);

            if ($wasOccupying !== $willOccupy) {
                $classRoom = ClassRoom::whereKey($lockedReg->class_id)->lockForUpdate()->firstOrFail();

                if ($willOccupy && ! $wasOccupying) {
                    $classRoom->increment('enrolled_count');
                } elseif (! $willOccupy && $wasOccupying && $classRoom->enrolled_count > 0) {
                    $classRoom->decrement('enrolled_count');
                }
            }

            $lockedReg->update(['status' => $newStatus]);

            if ($newStatus === 'refunded') {
                $lockedReg->payments()->where('status', 'paid')->update([
                    'status' => 'refunded',
                    'refund_notes' => $validated['refund_notes'] ?? 'Ditandai refund oleh Admin.',
                ]);
            } elseif ($newStatus === 'cancelled') {
                $lockedReg->payments()->where('status', 'pending')->update([
                    'status' => 'expired',
                ]);
            }
        });

        return back()->with('status', "Status pendaftaran {$registration->registration_number} berhasil diperbarui.");
    }

    public function verifyPayment(Request $request, Registration $registration): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        $confirmed = DB::transaction(function () use ($registration, $validated): bool {
            $lockedReg = Registration::whereKey($registration->id)->lockForUpdate()->firstOrFail();
            $payment = $lockedReg->payments()->latest()->lockForUpdate()->first();

            if (! $payment) {
                return false;
            }

            if ($payment->status !== 'paid') {
                $payment->update([
                    'status' => 'paid',
                    'payment_method' => $validated['payment_method'] ?: 'manual_admin',
                    'paid_at' => now(),
                ]);
            }

            if ($lockedReg->status === 'cancelled') {
                $classRoom = ClassRoom::whereKey($lockedReg->class_id)->lockForUpdate()->first();
                if ($classRoom) {
                    $classRoom->increment('enrolled_count');
                }
            }

            $lockedReg->update(['status' => 'confirmed']);

            return true;
        });

        if ($confirmed) {
            SendRegistrationConfirmedEmail::dispatch($registration->id);
            SendRegistrationConfirmedWhatsApp::dispatch($registration->id);
        }

        return back()->with('status', "Pembayaran {$registration->registration_number} berhasil diverifikasi manual & dikonfirmasi.");
    }

    public function export(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['search', 'program_id', 'status', 'date_from', 'date_to']);
        $format = $request->query('format') === 'csv' ? 'csv' : 'xlsx';
        $fileName = 'pendaftar-bimbel-'.now()->format('Ymd-His').".{$format}";

        return Excel::download(
            new RegistrationsExport($filters),
            $fileName,
            $format === 'csv' ? ExcelWriter::CSV : ExcelWriter::XLSX
        );
    }
}
