<?php

namespace App\Services;

use App\Models\Transaksi;

class NotificationService
{
    /**
     * Generate WhatsApp link for transaction completion
     * 
     * @param Transaksi $transaksi
     * @param string $stage
     * @return string
     */
    public function generateWhatsAppProgressLink(Transaksi $transaksi, string $stage): string
    {
        // Ganti 0 depannya dengan 62
        $phone = preg_replace('/^0/', '62', $transaksi->customer_phone);
        
        $msg = "Halo " . $transaksi->customer_name . ", pesanan Anda #" . $transaksi->transaksi_code . 
               " saat ini telah selesai pada tahap " . ucfirst($stage) . ". \n\n" .
               "Cek progress lengkapnya di: " . route('track.status', ['nota_number' => $transaksi->transaksi_code]);
        
        return "https://wa.me/" . $phone . "?text=" . urlencode($msg);
    }
}
