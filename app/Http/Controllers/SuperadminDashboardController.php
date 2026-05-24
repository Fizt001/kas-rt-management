<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Billing;
use App\Models\Agenda;
use Carbon\Carbon;

class SuperadminDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // 1. DATA SUPERADMIN (USER MANAGEMENT)
        $totalUsers = User::count();
        $totalWarga = User::where('role', 'warga')->count();
        $totalRt = User::where('role', 'rt')->count();
        $totalBendahara = User::where('role', 'bendahara')->count();
        $usersTerbaru = User::orderBy('created_at', 'desc')->take(5)->get();
        $dataRole = [
            $totalWarga,
            $totalRt,
            $totalBendahara,
            User::where('role', 'superadmin')->count()
        ];

        // 2. DATA KEUANGAN (BENDAHARA & RT)
        $totalPemasukan = Billing::where('status', 'lunas')->sum('total_amount');
        $totalPengeluaran = Agenda::sum('realisasi_dana');
        $saldoAktual = $totalPemasukan - $totalPengeluaran;
        $totalTunggakan = Billing::whereIn('status', ['belum_lunas', 'pending'])->sum('total_amount');
        $pendingVerifikasi = Billing::where('status', 'pending')->count();
        
        $pemasukanBulanIni = Billing::where('status', 'lunas')
            ->where('tahun', $now->year)
            ->where('bulan', $now->month)
            ->sum('total_amount');

        // Status Iuran Bulan Ini (Donut Chart)
        $statusBulanIni = Billing::where('tahun', $now->year)
            ->where('bulan', $now->month)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
            
        $dataStatusIuran = [
            $statusBulanIni['lunas'] ?? 0,
            $statusBulanIni['pending'] ?? 0,
            $statusBulanIni['belum_lunas'] ?? 0
        ];

        // 3. KEPATUHAN & DEMOGRAFI (RT)
        $wargaLunas = Billing::where('status', 'lunas')
            ->where('tahun', $now->year)
            ->where('bulan', $now->month)
            ->count();
        $persenKepatuhan = $totalWarga > 0 ? round(($wargaLunas / $totalWarga) * 100) : 0;

        $demografi = ['Balita (0-5)' => 0, 'Anak-anak (6-12)' => 0, 'Remaja (13-17)' => 0, 'Dewasa (18-59)' => 0, 'Lansia (60+)' => 0];
        $usersWargaDOB = User::where('role', 'warga')->whereNotNull('tanggal_lahir')->pluck('tanggal_lahir');
        $familyMembersDOB = \App\Models\FamilyMember::whereNotNull('tanggal_lahir')->pluck('tanggal_lahir');
        $allDOBs = $usersWargaDOB->merge($familyMembersDOB);
        $totalJiwa = count($allDOBs);

        foreach ($allDOBs as $dob) {
            $age = Carbon::parse($dob)->age;
            if ($age <= 5) $demografi['Balita (0-5)']++;
            elseif ($age <= 12) $demografi['Anak-anak (6-12)']++;
            elseif ($age <= 17) $demografi['Remaja (13-17)']++;
            elseif ($age <= 59) $demografi['Dewasa (18-59)']++;
            else $demografi['Lansia (60+)']++;
        }
        $dataDemografi = array_values($demografi);

        // 4. DAFTAR LISTING
        $antreanVerifikasi = Billing::with('user')
            ->where('status', 'pending')
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();
            
        $riwayatPengeluaran = Agenda::where('realisasi_dana', '>', 0)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        $agendaTerdekat = Agenda::where('tanggal', '>=', $now->startOfDay())
            ->orderBy('tanggal', 'asc')->take(4)->get();

        $topTunggakan = User::where('role', 'warga')
            ->withSum(['billings as total_tunggakan' => function($q) {
                $q->whereIn('status', ['belum_lunas', 'pending']);
            }], 'total_amount')
            ->having('total_tunggakan', '>', 0)
            ->orderByDesc('total_tunggakan')
            ->take(5)
            ->get();

        // 5. GRAFIK TREN (6 BULAN)
        $labelBulan = [];
        $dataPemasukan = [];
        $dataPengeluaranChart = [];
        $dataKepatuhan = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labelBulan[] = $date->translatedFormat('M');

            $lunasBulanIni = Billing::where('status', 'lunas')
                ->where('tahun', $date->year)
                ->where('bulan', $date->month)
                ->count();
            $dataKepatuhan[] = $lunasBulanIni;

            $masuk = Billing::where('status', 'lunas')
                ->where('tahun', $date->year)
                ->where('bulan', $date->month)
                ->sum('total_amount');
            $dataPemasukan[] = (int) $masuk;
            
            $keluar = Agenda::whereYear('tanggal', $date->year) 
                ->whereMonth('tanggal', $date->month)
                ->sum('realisasi_dana');
            $dataPengeluaranChart[] = (int) $keluar;
        }

        return view('superadmin.dashboard', compact(
            'totalUsers', 'totalWarga', 'totalRt', 'totalBendahara', 'usersTerbaru', 'dataRole',
            'saldoAktual', 'totalTunggakan', 'pemasukanBulanIni', 'pendingVerifikasi',
            'persenKepatuhan', 'totalJiwa', 'dataDemografi', 'dataStatusIuran',
            'antreanVerifikasi', 'riwayatPengeluaran', 'agendaTerdekat', 'topTunggakan',
            'labelBulan', 'dataPemasukan', 'dataPengeluaranChart', 'dataKepatuhan'
        ));
    }
}
