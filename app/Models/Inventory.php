<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Inventory extends Model
{
    use HasFactory, LogsActivity;

    // nama tabel (optional, kalau beda dari default)
    protected $table = 'inventories';

    protected $fillable = [
        'name',
        'category',
        'unit_type',
        'capacity_per_unit',
        'unit_of_measurement',
        'stock_units',
        'stock_subunits',
        'quantity',
        'minimum_stock',
        'type',
    ];

    protected $attributes = [
        'quantity' => 0,
        'stock_units' => 0,
        'stock_subunits' => 0,
        'capacity_per_unit' => 1,
        'unit_type' => 'botol',
        'unit_of_measurement' => 'ml',
        'minimum_stock' => 5,
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_units' => 'integer',
        'stock_subunits' => 'integer',
        'capacity_per_unit' => 'integer',
        'minimum_stock' => 'integer',
    ];

    /**
     * Deduct specific amount of stock (in ml or pcs).
     */
    public function deductStock(float $amount): void
    {
        if ($this->unit_of_measurement === 'pcs' || $this->capacity_per_unit <= 1) {
            // For pcs/single items, we just deduct from stock_units directly
            if ($this->stock_units < $amount) {
                throw new \Exception("Stok {$this->name} tidak mencukupi. Sisa: {$this->stock_units} pcs.");
            }
            $this->decrement('stock_units', $amount);
            $this->quantity = $this->stock_units;
            $this->save();
            return;
        }

        // For liquid/ml items (botol/sachet):
        // Total available ml = stock_units * capacity_per_unit + stock_subunits
        $totalAvailable = ($this->stock_units * $this->capacity_per_unit) + $this->stock_subunits;
        if ($totalAvailable < $amount) {
            throw new \Exception("Stok {$this->name} tidak mencukupi. Sisa: {$totalAvailable} ml.");
        }

        if ($this->stock_subunits >= $amount) {
            // We have enough in the active/opened bottle
            $this->decrement('stock_subunits', $amount);
        } else {
            // Active bottle is not enough, we need to open new bottle(s)
            $remainingNeed = $amount - $this->stock_subunits;
            $this->stock_subunits = 0;

            // Calculate how many full bottles we need to open
            $bottlesToOpen = ceil($remainingNeed / $this->capacity_per_unit);
            
            $this->decrement('stock_units', $bottlesToOpen);
            
            // Set the active subunit of the newly opened bottle
            $newActiveSubunit = ($bottlesToOpen * $this->capacity_per_unit) - $remainingNeed;
            $this->stock_subunits = $newActiveSubunit;
        }

        $this->quantity = $this->stock_units;
        $this->save();
    }

    /**
     * Adjust stock units directly (usually from admin or request adjustment).
     */
    public function adjustStockUnits(int $adjustment): void
    {
        $newUnits = $this->stock_units + $adjustment;
        if ($newUnits < 0) {
            throw new \Exception("Stok tidak bisa negatif.");
        }
        $this->stock_units = $newUnits;
        $this->quantity = $newUnits;
        $this->save();
    }
}