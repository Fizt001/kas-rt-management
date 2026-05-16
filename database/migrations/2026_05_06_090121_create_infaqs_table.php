<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infaqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('nominal', 12, 2);
            $table->enum('kategori', ['Mesjid', 'Anak Yatim', 'Sosial', 'Jumat Berkah']);
            $table->string('bukti_transfer');
            $table->enum('status', ['pending', 'terverifikasi', 'ditolak'])->default('pending');
            $table->text('pesan_doa')->nullable(); // Warga bisa nulis doa saat infaq
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infaqs');
    }
};
