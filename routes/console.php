<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use App\Models\IuranMaster;
use App\Models\Billing;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('iuran:generate-auto', function () {
    $this->info('Mengecek dan membuat tagihan otomatis...');
    $warga = User::where('role', 'warga')->get();
    $iuranAktif = IuranMaster::where('is_active', true)->get();
    
    if ($iuranAktif->isEmpty()) {
        $this->error('Gagal! Tidak ada Master Iuran yang aktif.');
        return;
    }

    $totalNominal = $iuranAktif->sum('nominal'); 
    $bulanIni = Carbon::now()->month;
    $tahunIni = Carbon::now()->year;
    
    $count = 0;

    foreach ($warga as $w) {
        $exists = Billing::where('user_id', $w->id)
            ->where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->exists();

        if (!$exists) {
            Billing::create([
                'user_id'      => $w->id,
                'bulan'        => $bulanIni,
                'tahun'        => $tahunIni,
                'total_amount' => $totalNominal, 
                'status'       => 'belum_lunas',
            ]);
            $count++;
        }
    }
    
    $this->info("Berhasil men-generate $count tagihan total baru untuk bulan $bulanIni/$tahunIni.");
})->purpose('Generate tagihan kas RT bulanan secara otomatis');

// Jalankan otomatis setiap tanggal 10 tiap bulannya pada jam 00:00
Schedule::command('iuran:generate-auto')->monthlyOn(10, '00:00');
