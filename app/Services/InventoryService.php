<?php

namespace App\Services;

use App\Models\Inventory;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Deduct standard washing supplies from inventory.
     * Usually 1 detergent and 1 fragrance.
     * 
     * @param int $transaksiId The ID of the transaction to log warning against
     * @param string $stage The stage of the task
     * @return void
     */
    public function deductWashingSupplies(int $transaksiId, string $stage): void
    {
        try {
            // Deduct 1 unit of detergent
            $detergent = Inventory::where('category', 'detergent')
                ->where('quantity', '>=', 1)
                ->lockForUpdate()
                ->first();
            
            if ($detergent) {
                $detergent->decrement('quantity', 1);
            } else {
                Log::warning('Detergent out of stock during task completion', [
                    'transaksi_id' => $transaksiId,
                    'stage' => $stage,
                ]);
            }

            // Deduct 1 unit of fragrance
            $fragrance = Inventory::where('category', 'fragrance')
                ->where('quantity', '>=', 1)
                ->lockForUpdate()
                ->first();
            
            if ($fragrance) {
                $fragrance->decrement('quantity', 1);
            } else {
                Log::warning('Fragrance out of stock during task completion', [
                    'transaksi_id' => $transaksiId,
                    'stage' => $stage,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to deduct washing supplies', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
