<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesjid_payments', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank'); // Contoh: BSI, Mandiri, BCA
            $table->string('atas_nama'); // Nama di rekening
            $table->string('nomor_rekening');
            $table->string('qris_image')->nullable(); // Gambar QRIS jika ada
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesjid_payments');
    }
};
