<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesjid_expenditures', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Contoh: Beli Karpet, Santunan Yatim
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->decimal('nominal', 12, 2);
            $table->string('kategori'); // Operasional, Pembangunan, Sosial, dll
            $table->string('bukti_nota')->nullable(); // Foto struk/kuitansi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesjid_expenditures');
    }
};
