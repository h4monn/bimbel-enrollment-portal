<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('services.midtrans.is_3ds', true);
    }

    /**
     * Membuat transaksi Midtrans Snap sebesar harga penuh program dengan batas waktu 24 jam.
     *
     * @return array{snap_token: string, redirect_url: string}
     */
    public function createSnapTransaction(Registration $registration, Payment $payment, User $user): array
    {
        $registration->loadMissing(['classRoom.program', 'student']);

        $programName = $registration->classRoom->program->name;
        $className = $registration->classRoom->name;

        $params = [
            'transaction_details' => [
                'order_id' => $payment->order_id,
                'gross_amount' => (int) $payment->amount,
            ],
            'item_details' => [
                [
                    'id' => 'CLASS-'.$registration->class_id,
                    'price' => (int) $payment->amount,
                    'quantity' => 1,
                    'name' => mb_substr("{$programName} - {$className}", 0, 50),
                ],
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->whatsapp,
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24,
            ],
        ];

        $snapResponse = Snap::createTransaction($params);

        return [
            'snap_token' => $snapResponse->token,
            'redirect_url' => $snapResponse->redirect_url,
        ];
    }

    /**
     * Validasi signature_key dari webhook Midtrans:
     * SHA512(order_id + status_code + gross_amount + ServerKey)
     */
    public function isValidSignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = (string) config('services.midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }
}
