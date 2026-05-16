<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('koperasi_payments', function (Blueprint $table) {
        $table->id();
        $table->string('nama_bank');
        $table->string('nomor_rekening');
        $table->string('atas_nama');
        $table->string('qris_image')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koperasi_payments');
    }
};
