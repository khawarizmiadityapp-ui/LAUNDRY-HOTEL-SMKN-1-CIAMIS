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
        // Ganti ke format standar WhatsApp
        $phone = format_whatsapp_number($transaksi->customer_phone);
        
        $msg = "Halo " . $transaksi->customer_name . ", pesanan Anda #" . $transaksi->transaksi_code . 
               " saat ini telah selesai pada tahap " . ucfirst($stage) . ". \n\n" .
               "Cek progress lengkapnya di: " . route('track.status', ['nota_number' => $transaksi->transaksi_code]);
        
        return "https://wa.me/" . $phone . "?text=" . urlencode($msg);
    }
}
