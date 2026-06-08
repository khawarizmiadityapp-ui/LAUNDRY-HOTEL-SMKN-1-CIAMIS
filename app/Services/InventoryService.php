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

        // 2. Fetch dan Lock DB dalam 1 query (hindari N+1 query problem). 
        // Order by ID berguna untuk mencegah deadlock saat transaksi konkuren.
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
            if ($inventory->quantity < $quantity) {
                throw new \Exception("Stok '{$inventory->name}' tidak mencukupi. Sisa stok: {$inventory->quantity}, dibutuhkan: {$quantity}.");
            }
        }

        // 4. Eksekusi pemotongan stok
        foreach ($aggregatedMaterials as $id => $quantity) {
            $inventories[$id]->decrement('quantity', $quantity);
        }
    }
}
