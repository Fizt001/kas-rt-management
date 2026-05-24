<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Billing;
use App\Models\Agenda;
use Carbon\Carbon;

class RtDashboardController extends Controller
{
    public function index()
    {
        // 1. DATA KARTU METRIK
        $totalWarga = User::where('role', 'warga')->count();
        
        $totalPemasukan = Billing::where('status', 'lunas')->sum('total_amount');
        $totalPengeluaran = Agenda::sum('realisasi_dana');
        $saldoAktual = $totalPemasukan - $totalPengeluaran;
        
        $totalTunggakan = Billing::whereIn('status', ['belum_lunas', 'pending'])->sum('total_amount');

        // --- LOGIKA KEPATUHAN ---
        $now = \Carbon\Carbon::now();
        $bulanSekarang = $now->month;
        $tahunSekarang = $now->year;
        
        $wargaLunas = Billing::where('status', 'lunas')
            ->where('tahun', $tahunSekarang)
            ->where('bulan', $bulanSekarang)
            ->count();
            
        $persenKepatuhan = $totalWarga > 0 ? round(($wargaLunas / $totalWarga) * 100) : 0;

        // 2. LIST AGENDA TERDEKAT
        $agendaTerdekat = Agenda::where('tanggal', '>=', \Carbon\Carbon::now()->startOfDay())
            ->orderBy('tanggal', 'asc')
            ->take(4)
            ->get();

        // 3. TOP 5 TUNGGAKAN
        $topTunggakan = User::where('role', 'warga')
            ->withSum(['billings as total_tunggakan' => function($q) {
                $q->whereIn('status', ['belum_lunas', 'pending']);
            }], 'total_amount')
            ->having('total_tunggakan', '>', 0)
            ->orderByDesc('total_tunggakan')
            ->take(5)
            ->get();

        // 4. DATA GRAFIK (6 BULAN TERAKHIR)
        $labelBulan = [];
        $dataKepatuhan = [];
        $dataPemasukan = [];
        $dataPengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $labelBulan[] = $date->translatedFormat('M'); 

            $lunas = \App\Models\Billing::where('status', 'lunas')
                ->where('tahun', $date->year)
                ->where('bulan', $date->month)
                ->count();
            $dataKepatuhan[] = $lunas;

            $masuk = \App\Models\Billing::where('status', 'lunas')
                ->where('tahun', $date->year)
                ->where('bulan', $date->month)
                ->sum('total_amount');
            $dataPemasukan[] = (int) $masuk;

            $keluar = Agenda::whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('realisasi_dana');
            $dataPengeluaran[] = (int) $keluar;
        }

        // 5. DATA DEMOGRAFI UMUR WARGA
        $demografi = [
            'Balita (0-5)' => 0,
            'Anak-anak (6-12)' => 0,
            'Remaja (13-17)' => 0,
            'Dewasa (18-59)' => 0,
            'Lansia (60+)' => 0,
        ];

        // Hitung umur dari user warga
        $usersWarga = User::where('role', 'warga')->whereNotNull('tanggal_lahir')->pluck('tanggal_lahir');
        // Hitung umur dari family members
        $familyMembers = \App\Models\FamilyMember::whereNotNull('tanggal_lahir')->pluck('tanggal_lahir');

        $allDOBs = $usersWarga->merge($familyMembers);

        foreach ($allDOBs as $dob) {
            $age = Carbon::parse($dob)->age;
            if ($age <= 5) $demografi['Balita (0-5)']++;
            elseif ($age <= 12) $demografi['Anak-anak (6-12)']++;
            elseif ($age <= 17) $demografi['Remaja (13-17)']++;
            elseif ($age <= 59) $demografi['Dewasa (18-59)']++;
            else $demografi['Lansia (60+)']++;
        }

        $dataDemografi = array_values($demografi);
        $totalJiwa = count($allDOBs);

        return view('rt.dashboard', compact(
            'totalWarga', 'totalJiwa', 'saldoAktual', 'totalTunggakan', 'persenKepatuhan', 
            'agendaTerdekat', 'topTunggakan', 'labelBulan', 'dataKepatuhan', 
            'dataPemasukan', 'dataPengeluaran', 'dataDemografi'
        ));
    }
}