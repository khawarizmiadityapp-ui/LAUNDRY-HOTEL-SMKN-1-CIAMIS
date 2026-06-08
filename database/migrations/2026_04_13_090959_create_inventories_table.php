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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // detergent, fragrance, hanger, packaging, lainnya
            $table->string('unit_type')->default('botol'); // botol, sachet, pcs
            $table->integer('capacity_per_unit')->default(1); // e.g. 1000 for 1L botol, 20 for sachet, 1 for pcs
            $table->string('unit_of_measurement')->default('ml'); // ml, pcs
            $table->integer('stock_units')->default(0); // number of unopened / full packages in warehouse
            $table->integer('stock_subunits')->default(0); // ml/pcs of the open/active package
            $table->integer('quantity')->default(0); // compatibility with old stock units
            $table->integer('minimum_stock')->default(5);
            $table->string('type')->nullable(); // description / subtype (Heavy Duty, etc)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
