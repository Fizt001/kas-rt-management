<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenditures', function (Blueprint $table) {
            $table->id();
            $table->string('nama_agenda');
            $table->boolean('butuh_dana')->default(false);
            $table->decimal('nominal', 12, 2)->default(0);
            $table->string('bukti_kegiatan')->nullable(); // Foto acara
            $table->string('nota_belanja')->nullable();   // Foto struk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenditures');
    }
};
