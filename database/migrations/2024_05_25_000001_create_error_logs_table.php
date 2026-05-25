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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('severity', ['INFO', 'WARNING', 'ERROR', 'CRITICAL'])->default('ERROR');
            $table->text('message');
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->text('trace')->nullable();
            $table->string('context')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_email')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->json('additional_data')->nullable();
            $table->boolean('resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            
            $table->index(['severity', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['resolved', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
