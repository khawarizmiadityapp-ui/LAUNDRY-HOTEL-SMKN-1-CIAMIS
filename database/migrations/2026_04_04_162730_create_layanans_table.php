<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['kiloan', 'satuan']);
            $table->decimal('harga', 10, 2);
            $table->string('estimasi')->nullable(); // e.g. "1 hari pengerjaan"
            $table->boolean('status')->default(true); // aktif/nonaktif
            $table->string('badge')->nullable(); // e.g. "Populer", "Lunas"
            $table->string('icon')->default('shirt'); // icon identifier
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
