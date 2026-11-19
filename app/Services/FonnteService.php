<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendWhatsApp(string $target, string $message): bool
    {
        $token = config('services.fonnte.token');
        $baseUrl = rtrim((string) config('services.fonnte.base_url', 'https://api.fonnte.com'), '/');

        if (empty($token)) {
            Log::warning('Fonnte token belum dikonfigurasi. Pesan WhatsApp tidak dikirim.', [
                'target' => $target,
            ]);

            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->timeout(15)->post("{$baseUrl}/send", [
            'target' => $target,
            'message' => $message,
            'countryCode' => '62',
        ]);

        if (! $response->successful()) {
            Log::error('Gagal mengirim WhatsApp via Fonnte.', [
                'target' => $target,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        return true;
    }
}
