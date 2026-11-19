<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\SendRegistrationConfirmedEmail;
use App\Jobs\SendRegistrationConfirmedWhatsApp;
use App\Models\ClassRoom;
use App\Models\Payment;
use App\Models\Registration;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');
        $paymentType = $payload['payment_type'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        if (! $this->midtransService->isValidSignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans webhook signature tidak valid.', ['order_id' => $orderId]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $outcome = DB::transaction(function () use (
            $orderId,
            $transactionStatus,
            $fraudStatus,
            $paymentType,
            $transactionId,
            $payload
        ): array {
            $payment = Payment::where('order_id', $orderId)->lockForUpdate()->first();

            if (! $payment) {
                return ['status' => 404, 'message' => 'Payment not found', 'dispatch' => false];
            }

            // Idempotency check: abaikan duplikasi notifikasi jika sudah paid
            if ($payment->status === 'paid') {
                return ['status' => 200, 'message' => 'Already processed', 'dispatch' => false];
            }

            $registration = Registration::whereKey($payment->registration_id)->lockForUpdate()->first();

            if (! $registration) {
                return ['status' => 404, 'message' => 'Registration not found', 'dispatch' => false];
            }

            $isSuccess = $transactionStatus === 'settlement'
                || ($transactionStatus === 'capture' && ($fraudStatus === '' || $fraudStatus === 'accept'));

            if ($isSuccess) {
                // Penanganan kasus pembayaran ganda (registration sudah lunas/dikonfirmasi lewat transaksi lain)
                $hasExistingPaid = Payment::where('registration_id', $registration->id)
                    ->where('id', '!=', $payment->id)
                    ->where('status', 'paid')
                    ->exists();

                if ($hasExistingPaid || in_array($registration->status, ['paid', 'confirmed'], true)) {
                    $payment->update([
                        'status' => 'paid',
                        'payment_method' => $paymentType,
                        'gateway_reference' => $transactionId,
                        'paid_at' => now(),
                        'refund_notes' => 'Pembayaran ganda terdeteksi otomatis oleh webhook. Kandidat refund sesuai kebijakan PRD.',
                        'raw_payload' => $payload,
                    ]);

                    Log::warning('Pembayaran ganda terdeteksi pada pendaftaran.', [
                        'registration_id' => $registration->id,
                        'order_id' => $orderId,
                    ]);

                    return ['status' => 200, 'message' => 'Double payment recorded for refund review', 'dispatch' => false];
                }

                $payment->update([
                    'status' => 'paid',
                    'payment_method' => $paymentType,
                    'gateway_reference' => $transactionId,
                    'paid_at' => now(),
                    'raw_payload' => $payload,
                ]);

                // Jika pendaftaran sempat ter-cancel sebelum webhook settlement masuk, kunci dan tambah kuota kembali jika memungkinkan
                if ($registration->status === 'cancelled') {
                    $classRoom = ClassRoom::whereKey($registration->class_id)->lockForUpdate()->first();
                    if ($classRoom) {
                        $classRoom->increment('enrolled_count');
                    }
                }

                $registration->update(['status' => 'confirmed']);

                return [
                    'status' => 200,
                    'message' => 'Payment confirmed',
                    'dispatch' => true,
                    'registration_id' => $registration->id,
                ];
            }

            if (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
                $newPaymentStatus = $transactionStatus === 'expire' ? 'expired' : 'failed';

                $payment->update([
                    'status' => $newPaymentStatus,
                    'payment_method' => $paymentType,
                    'gateway_reference' => $transactionId,
                    'raw_payload' => $payload,
                ]);

                if ($registration->status === 'pending_payment') {
                    $registration->update(['status' => 'cancelled']);

                    $classRoom = ClassRoom::whereKey($registration->class_id)->lockForUpdate()->first();
                    if ($classRoom && $classRoom->enrolled_count > 0) {
                        $classRoom->decrement('enrolled_count');
                    }
                }

                return ['status' => 200, 'message' => 'Payment cancelled/expired', 'dispatch' => false];
            }

            // Status pending / challenge lainnya: simpan raw_payload terbaru
            $payment->update(['raw_payload' => $payload]);

            return ['status' => 200, 'message' => 'Webhook acknowledged', 'dispatch' => false];
        });

        if (! empty($outcome['dispatch']) && ! empty($outcome['registration_id'])) {
            SendRegistrationConfirmedEmail::dispatch($outcome['registration_id']);
            SendRegistrationConfirmedWhatsApp::dispatch($outcome['registration_id']);
        }

        return response()->json(['message' => $outcome['message']], $outcome['status']);
    }
}
