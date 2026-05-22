<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Standardisasi kolom bulan dari berbagai format (string nama, string angka, integer)
     * menjadi integer 1-12 yang konsisten.
     */
    public function up(): void
    {
        // Peta nama bulan Indonesia & Inggris ke angka
        $bulanMap = [
            // Nama bulan Indonesia
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
            // Nama bulan Inggris
            'january' => 1, 'february' => 2, 'march' => 3,
            'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
            'october' => 10, 'december' => 12,
            // Singkatan
            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4,
            'jun' => 6, 'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10,
            'nov' => 11, 'dec' => 12,
        ];

        // Ambil semua data billing
        $billings = DB::table('billings')->get();

        foreach ($billings as $billing) {
            $bulan = trim(strtolower($billing->bulan));
            $angka = null;

            // Jika sudah angka, cast saja
            if (is_numeric($bulan)) {
                $angka = (int) $bulan;
            } elseif (isset($bulanMap[$bulan])) {
                $angka = $bulanMap[$bulan];
            }

            // Update jika berhasil dipetakan
            if ($angka !== null && $angka >= 1 && $angka <= 12) {
                DB::table('billings')->where('id', $billing->id)->update(['bulan' => $angka]);
            }
        }

        // Ubah tipe kolom menjadi tinyInteger (1-12)
        Schema::table('billings', function (Blueprint $table) {
            $table->tinyInteger('bulan')->change();
        });
    }

    /**
     * Reverse the migrations. (Kembalikan ke string jika perlu rollback)
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->string('bulan')->change();
        });
    }
};
