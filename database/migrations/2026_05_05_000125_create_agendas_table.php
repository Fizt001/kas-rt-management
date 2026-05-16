<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            
            // --- KOLOM BAWAAN SISTEM AGENDA KAMU ---
            // Asumsi tabel users ada, maka kita pakai foreignId
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            $table->string('judul');
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->time('waktu')->nullable();
            $table->string('lokasi')->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            
            // --- KOLOM TAMBAHAN UNTUK PENGGUNAAN DANA ---
            $table->decimal('realisasi_dana', 12, 2)->default(0); 
            $table->string('bukti_kegiatan')->nullable(); 
            $table->string('nota_belanja')->nullable();   
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};