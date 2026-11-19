<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Services\RegistrationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpirePendingRegistrations extends Command
{
    protected $signature = 'registrations:expire-pending';

    protected $description = 'Membatalkan pendaftaran pending_payment yang melewati batas waktu 24 jam dan mengembalikan kuota kelas.';

    public function handle(RegistrationService $registrationService): int
    {
        $expiredCount = 0;

        Registration::where('status', 'pending_payment')
            ->where('expired_at', '<=', now())
            ->chunkById(100, function ($registrations) use ($registrationService, &$expiredCount): void {
                foreach ($registrations as $registration) {
                    $registrationService->releaseCancelledRegistration($registration, 'expired');
                    $expiredCount++;
                }
            });

        if ($expiredCount > 0) {
            Log::info("Auto-expire scheduler: {$expiredCount} pendaftaran kedaluwarsa dibatalkan dan kuota dikembalikan.");
        }

        $this->info("Berhasil memproses {$expiredCount} pendaftaran kedaluwarsa.");

        return self::SUCCESS;
    }
}
