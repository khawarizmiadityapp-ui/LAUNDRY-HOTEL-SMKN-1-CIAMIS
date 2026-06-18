<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->string('unit_type')->default('botol')->after('category');
            $table->integer('capacity_per_unit')->default(1)->after('unit_type');
            $table->string('unit_of_measurement')->default('ml')->after('capacity_per_unit');
            $table->integer('stock_units')->default(0)->after('unit_of_measurement');
            $table->integer('stock_subunits')->default(0)->after('stock_units');
            $table->integer('minimum_stock')->default(5)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn([
                'unit_type',
                'capacity_per_unit',
                'unit_of_measurement',
                'stock_units',
                'stock_subunits',
                'minimum_stock'
            ]);
        });
    }
};
