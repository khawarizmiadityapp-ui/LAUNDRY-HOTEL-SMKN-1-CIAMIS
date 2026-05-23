<?php

namespace App\Services;

use App\Models\ServicePrice;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    /**
     * Generate a unique transaction code.
     */
    public function generateTransactionCode(): string
    {
        $maxAttempts = 5;
        $attempt = 0;

        do {
            $randomStr = strtoupper(\Illuminate\Support\Str::random(4));
            $code = 'TRX-' . date('Ymd') . '-' . $randomStr;

            // Check if code already exists
            $exists = Transaksi::where('transaksi_code', $code)->exists();

            if (!$exists) {
                return $code;
            }

            $attempt++;
        } while ($attempt < $maxAttempts);

        // Fallback: use timestamp + random for guaranteed uniqueness
        return 'TRX-' . date('Ymd') . '-' . time() . '-' . strtoupper(\Illuminate\Support\Str::random(2));
    }

    /**
     * Get price per kg for a specific service type.
     */
    public function getPricePerKg(string $serviceType): float
    {
        $price = ServicePrice::where('service_type', $serviceType)->first();
        return $price ? $price->price_per_kg : 6000;
    }

    /**
     * Store transaction details and automatically create tracking tasks.
     * @param Transaksi $transaksi
     * @param array $items Array of ['layanan_id' => x, 'qty' => y, 'price' => z, 'subtotal' => w, 'layanan_obj' => Layanan]
     */
    public function storeTransactionDetailsAndTasks(Transaksi $transaksi, array $items)
    {
        $needsWashing = false;
        $needsIroning = false;
        $needsPacking = false;

        foreach ($items as $item) {
            $transaksi->details()->create([
                'layanan_id' => $item['layanan_id'],
                'qty'        => $item['qty'],
                'price'      => $item['price'],
                'subtotal'   => $item['subtotal'],
            ]);

            $l = $item['layanan_obj'];
            if ($l) {
                if ($l->needs_washing === null || $l->needs_washing == true) $needsWashing = true;
                if ($l->needs_ironing === null || $l->needs_ironing == true) $needsIroning = true;
                if ($l->needs_packing === null || $l->needs_packing == true) $needsPacking = true;
            }
        }

        if ($needsWashing) {
            $transaksi->tasks()->create(['stage' => 'washing', 'status' => 'pending']);
        }
        if ($needsIroning) {
            $transaksi->tasks()->create(['stage' => 'ironing', 'status' => 'pending']);
        }
        if ($needsPacking) {
            $transaksi->tasks()->create(['stage' => 'packing', 'status' => 'pending']);
        }
    }
}
