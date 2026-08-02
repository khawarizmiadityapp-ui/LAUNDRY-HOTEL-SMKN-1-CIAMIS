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
        Schema::create('daily_targets', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('base_target')->default(0)->comment('Target dasar harian');
            $table->integer('carry_forward')->default(0)->comment('Deficit/surplus dari hari sebelumnya');
            $table->integer('adjusted_target')->default(0)->comment('Target final (base + carry_forward)');
            $table->integer('actual_income')->default(0)->comment('Realisasi pemasukan hari ini');
            $table->integer('actual_expense')->default(0)->comment('Realisasi pengeluaran hari ini');
            $table->integer('net_income')->default(0)->comment('Pemasukan bersih (income - expense)');
            $table->integer('variance')->default(0)->comment('Selisih (net_income - adjusted_target)');
            $table->boolean('is_achieved')->default(false)->comment('Apakah target tercapai');
            $table->timestamps();
            
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_targets');
    }
};
