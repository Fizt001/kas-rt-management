<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('koperasi_accounts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->decimal('saldo_pokok', 15, 2)->default(0);    // Dibayar 1x saat gabung
        $table->decimal('saldo_wajib', 15, 2)->default(0);    // Dibayar rutin tiap bulan
        $table->decimal('saldo_sukarela', 15, 2)->default(0); // Tabungan bebas
        $table->decimal('total_saldo', 15, 2)->default(0);    // Akumulasi ketiganya
        $table->timestamps();
    });

    // Tabel histori transaksi (Masuk/Keluar)
    Schema::create('koperasi_transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->enum('type', ['setoran', 'penarikan', 'pinjaman', 'angsuran']);
        $table->string('kategori'); // Pokok, Wajib, Sukarela
        $table->decimal('amount', 15, 2);
        $table->string('keterangan')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->string('bukti_transfer')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koperasi_accounts');
    }
};
