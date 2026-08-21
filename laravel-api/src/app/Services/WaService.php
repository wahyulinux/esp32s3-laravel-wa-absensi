<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WaService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.wa_gateway.url', env('WA_GATEWAY_URL', 'http://wa-gateway:3001')), '/');
    }

    public function send(string $to, string $message): bool
    {
        if (blank($to)) {
            return false;
        }

        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/send", [
                'to'      => $to,
                'message' => $message,
            ]);

            if (! $response->successful()) {
                Log::warning('[WA] Gagal kirim pesan', ['to' => $to, 'status' => $response->status(), 'body' => $response->body()]);
                return false;
            }

            return (bool) ($response->json('success') ?? false);
        } catch (\Throwable $e) {
            Log::error('[WA] Exception saat kirim pesan', ['to' => $to, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendImage(string $to, string $caption, string $storagePath): bool
    {
        if (blank($to)) {
            return false;
        }

        try {
            $imageData = base64_encode(Storage::disk('public')->get($storagePath));

            $response = Http::timeout(30)->post("{$this->baseUrl}/send-image", [
                'to'           => $to,
                'caption'      => $caption,
                'image_base64' => $imageData,
            ]);

            if (! $response->successful()) {
                Log::warning('[WA] Gagal kirim foto', ['to' => $to, 'status' => $response->status(), 'body' => $response->body()]);
                return false;
            }

            return (bool) ($response->json('success') ?? false);
        } catch (\Throwable $e) {
            Log::error('[WA] Exception saat kirim foto', ['to' => $to, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function status(): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/status");
            if ($response->successful()) {
                return [
                    'online'    => true,
                    'connected' => (bool) ($response->json('connected') ?? false),
                    'qr'        => (bool) ($response->json('qr') ?? false),
                ];
            }
        } catch (\Throwable) {
        }
        return ['online' => false, 'connected' => false, 'qr' => false];
    }

    public function isConnected(): bool
    {
        return $this->status()['connected'];
    }

    public function disconnect(): array
    {
        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/logout");
            return $response->json() ?? ['success' => true];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
