<?php

namespace App\Services;

use App\Models\Inventory;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Deduct standard washing supplies from inventory.
     * Usually 1 detergent and 1 fragrance (or 50ml / 20ml respectively if bottle-based).
     * 
     * @param int $transaksiId The ID of the transaction to log warning against
     * @param string $stage The stage of the task
     * @return void
     */
    public function deductWashingSupplies(int $transaksiId, string $stage): void
    {
        try {
            // Deduct detergent
            $detergent = Inventory::where('category', 'detergent')
                ->lockForUpdate()
                ->first();
            
            if ($detergent) {
                // Default deduction: 50 ml for liquid/ml detergents, 1 pc for others
                $amount = $detergent->unit_of_measurement === 'ml' ? 50 : 1;
                $detergent->deductStock($amount);
            } else {
                Log::warning('Detergent out of stock during task completion', [
                    'transaksi_id' => $transaksiId,
                    'stage' => $stage,
                ]);
            }

            // Deduct fragrance
            $fragrance = Inventory::where('category', 'fragrance')
                ->lockForUpdate()
                ->first();
            
            if ($fragrance) {
                // Default deduction: 20 ml for liquid/ml fragrances, 1 pc for others
                $amount = $fragrance->unit_of_measurement === 'ml' ? 20 : 1;
                $fragrance->deductStock($amount);
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

    /**
     * Deduct custom washing supplies based on user input.
     * 
     * @param int $transaksiId The ID of the transaction to log warning against
     * @param string $stage The stage of the task
     * @param array $materials Array of materials and their quantities [['id' => 1, 'quantity' => 10], ...]
     * @return void
     */
    public function deductCustomWashingSupplies(int $transaksiId, string $stage, array $materials): void
    {
        // 1. Agregasi data (gabungkan kuantitas jika ada ID bahan yang sama dipilih dua kali)
        $aggregatedMaterials = [];
        foreach ($materials as $material) {
            if (!isset($material['id']) || !isset($material['quantity'])) continue;

            $id = $material['id'];
            $qty = (float) $material['quantity'];
            
            if ($qty <= 0) continue;

            if (isset($aggregatedMaterials[$id])) {
                $aggregatedMaterials[$id] += $qty;
            } else {
                $aggregatedMaterials[$id] = $qty;
            }
        }

        if (empty($aggregatedMaterials)) {
            return;
        }

        $ids = array_keys($aggregatedMaterials);

        // 2. Fetch dan Lock DB
        $inventories = Inventory::whereIn('id', $ids)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        // 3. Validasi stok semua bahan sebelum memotong
        foreach ($aggregatedMaterials as $id => $quantity) {
            if (!$inventories->has($id)) {
                throw new \Exception("Bahan dari gudang dengan ID {$id} tidak ditemukan.");
            }

            $inventory = $inventories[$id];
            $totalAvailable = ($inventory->stock_units * $inventory->capacity_per_unit) + $inventory->stock_subunits;
            if ($totalAvailable < $quantity) {
                $unitName = $inventory->unit_of_measurement;
                throw new \Exception("Stok '{$inventory->name}' tidak mencukupi. Sisa stok: {$totalAvailable} {$unitName}, dibutuhkan: {$quantity} {$unitName}.");
            }
        }

        // 4. Eksekusi pemotongan stok
        foreach ($aggregatedMaterials as $id => $quantity) {
            $inventories[$id]->deductStock($quantity);
        }
    }
}
