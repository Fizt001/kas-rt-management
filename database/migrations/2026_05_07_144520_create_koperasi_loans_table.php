<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('koperasi_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('amount'); // Nominal total yang dipinjam
            $table->integer('tenor'); // Berapa bulan dicicil
            $table->text('alasan'); // Alasan butuh dana
            $table->enum('status', ['pending', 'approved', 'rejected', 'lunas'])->default('pending');
            $table->timestamp('approved_at')->nullable(); // Kapan disetujui Admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koperasi_loans');
    }
};
