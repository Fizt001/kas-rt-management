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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
          
            // Kolom periode tagihan
            $table->string('bulan'); // Januari - Desember
            $table->integer('tahun');
            $table->decimal('total_amount', 12, 2);
            
            // UBAH 1: Sesuaikan status dengan logika aplikasi
            $table->enum('status', ['belum_lunas', 'pending', 'lunas'])->default('belum_lunas');
            
            // UBAH 2: Samakan nama kolom dengan Controller (bukti_transfer)
            $table->string('bukti_transfer')->nullable(); 
            
            $table->timestamp('verified_at')->nullable(); // Kapan bendahara acc
            $table->integer('verified_by')->nullable(); // User ID Bendahara
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};