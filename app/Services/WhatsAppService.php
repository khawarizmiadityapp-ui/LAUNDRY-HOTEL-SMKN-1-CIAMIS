<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiKey;
    protected $baseUrl;
    protected $enabled;

    public function __construct()
    {
        $this->apiKey = config('services.whatsapp.api_key');
        $this->baseUrl = config('services.whatsapp.base_url', 'https://api.fonnte.com');
        $this->enabled = config('services.whatsapp.enabled', false);
    }

    /**
     * Send WhatsApp message
     *
     * @param string $to Phone number (format: 628123456789)
     * @param string $message Message content
     * @param array $options Additional options (delay, etc)
     * @return array Response from API
     */
    public function sendMessage(string $to, string $message, array $options = []): array
    {
        if (!$this->enabled) {
            Log::info('WhatsApp service is disabled', [
                'to' => $to,
                'message' => $message,
            ]);
            return [
                'success' => false,
                'message' => 'WhatsApp service is disabled',
            ];
        }

        try {
            // Clean phone number
            $to = $this->cleanPhoneNumber($to);

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post($this->baseUrl . '/send', [
                'target' => $to,
                'message' => $message,
                'delay' => $options['delay'] ?? 2,
            ]);

            $result = $response->json();

            Log::info('WhatsApp message sent', [
                'to' => $to,
                'status' => $result['status'] ?? 'unknown',
                'response' => $result,
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp send failed', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send notification for laundry status update
     *
     * @param \App\Models\Transaksi $transaksi
     * @param string $status New status
     * @return array
     */
    public function sendStatusNotification($transaksi, string $status): array
    {
        $statusMessages = [
            'diterima' => 'Pesanan Anda telah diterima dan sedang diproses.',
            'dicuci' => 'Pesanan Anda sedang dalam proses pencucian.',
            'disetrika' => 'Pesanan Anda sedang dalam proses setrika.',
            'dipacking' => 'Pesanan Anda sedang dalam proses packing.',
            'selesai' => 'Pesanan Anda telah selesai dan siap diambil.',
            'diambil' => 'Terima kasih telah menggunakan layanan kami. Pesanan Anda telah diambil.',
        ];

        $message = $statusMessages[$status] ?? 'Status pesanan Anda telah diperbarui.';

        $fullMessage = "Halo {$transaksi->customer_name},\n\n" .
            "{$message}\n\n" .
            "Detail Pesanan:\n" .
            "Kode: {$transaksi->transaksi_code}\n" .
            "Total: Rp " . number_format($transaksi->total_price, 0, ',', '.') . "\n\n" .
            "Terima kasih telah menggunakan layanan Laundry Hotel SMKN 1 Ciamis!";

        return $this->sendMessage($transaksi->customer_phone, $fullMessage);
    }

    /**
     * Send reminder for pickup
     *
     * @param \App\Models\Transaksi $transaksi
     * @return array
     */
    public function sendPickupReminder($transaksi): array
    {
        $message = "Halo {$transaksi->customer_name},\n\n" .
            "Pesanan Anda dengan kode {$transaksi->transaksi_code} sudah selesai dan siap diambil.\n\n" .
            "Total: Rp " . number_format($transaksi->total_price, 0, ',', '.') . "\n\n" .
            "Silakan datang ke outlet kami untuk mengambil pesanan Anda.\n\n" .
            "Terima kasih!";

        return $this->sendMessage($transaksi->customer_phone, $message);
    }

    /**
     * Clean phone number format
     *
     * @param string $phone
     * @return string
     */
    protected function cleanPhoneNumber(string $phone): string
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // If doesn't start with 62, add it
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Check if service is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
