<?php

namespace App\Exports;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrationsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(
        protected array $filters = [],
    ) {}

    public function query(): Builder
    {
        return Registration::query()
            ->with(['user', 'student', 'classRoom.program', 'latestPayment'])
            ->when(! empty($this->filters['search']), function (Builder $query): void {
                $search = $this->filters['search'];
                $query->where(function (Builder $q) use ($search): void {
                    $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhereHas('student', fn (Builder $sq) => $sq->where('full_name', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn (Builder $uq) => $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('whatsapp', 'like', "%{$search}%"));
                });
            })
            ->when(! empty($this->filters['program_id']), function (Builder $query): void {
                $programId = $this->filters['program_id'];
                $query->whereHas('classRoom', fn (Builder $cq) => $cq->where('program_id', $programId));
            })
            ->when(! empty($this->filters['status']), function (Builder $query): void {
                $query->where('status', $this->filters['status']);
            })
            ->when(! empty($this->filters['date_from']), function (Builder $query): void {
                $query->whereDate('created_at', '>=', $this->filters['date_from']);
            })
            ->when(! empty($this->filters['date_to']), function (Builder $query): void {
                $query->whereDate('created_at', '<=', $this->filters['date_to']);
            })
            ->latest();
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'Tanggal Daftar',
            'Nama Siswa',
            'Asal Sekolah',
            'Jenjang / Kelas',
            'Nama Wali',
            'Email Wali',
            'No. WhatsApp',
            'Program',
            'Kelas',
            'Jadwal',
            'Total Biaya (Rp)',
            'Status Pendaftaran',
            'Status Pembayaran',
            'Metode Pembayaran',
            'Tanggal Lunas',
        ];
    }

    /**
     * @param  Registration  $registration
     * @return array<int, mixed>
     */
    public function map($registration): array
    {
        return [
            $registration->registration_number,
            $registration->created_at?->format('Y-m-d H:i'),
            $registration->student?->full_name,
            $registration->student?->school_name,
            trim(($registration->student?->education_level ?? '').' '.($registration->student?->grade ?? '')),
            $registration->user?->name,
            $registration->user?->email,
            $registration->user?->whatsapp,
            $registration->classRoom?->program?->name,
            $registration->classRoom?->name,
            trim(($registration->classRoom?->schedule_days ?? '').' '.($registration->classRoom?->schedule_time ?? '')),
            $registration->total_amount,
            $registration->status,
            $registration->latestPayment?->status ?? '-',
            $registration->latestPayment?->payment_method ?? '-',
            $registration->latestPayment?->paid_at?->format('Y-m-d H:i') ?? '-',
        ];
    }
}
