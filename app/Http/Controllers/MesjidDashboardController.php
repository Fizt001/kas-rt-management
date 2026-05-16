<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infaq;
use App\Models\MesjidExpenditure;
use Carbon\Carbon;

class MesjidDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $bulanIni = $now->month;
        $tahunIni = $now->year;

        // 1. DATA 3 KARTU UTAMA
        $totalInfaqAll = Infaq::where('status', 'terverifikasi')->sum('nominal');
        $totalKeluarAll = MesjidExpenditure::sum('nominal');
        $saldoAktual = $totalInfaqAll - $totalKeluarAll;

        $masukBulanIni = Infaq::where('status', 'terverifikasi')
            ->whereYear('created_at', $tahunIni)
            ->whereMonth('created_at', $bulanIni)
            ->sum('nominal');

        $keluarBulanIni = MesjidExpenditure::whereYear('tanggal', $tahunIni)
            ->whereMonth('tanggal', $bulanIni)
            ->sum('nominal');

        // 2. DATA GRAFIK (7 HARI TERAKHIR)
        $labelHari = [];
        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labelHari[] = $date->translatedFormat('D'); // Sen, Sel, Rab...

            $dataMasuk[] = (int) Infaq::where('status', 'terverifikasi')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->sum('nominal');

            $dataKeluar[] = (int) MesjidExpenditure::whereDate('tanggal', $date->format('Y-m-d'))
                ->sum('nominal');
        }

        // 3. AKTIVITAS TERKINI (Gabung Infaq Masuk & Pengeluaran)
        $latestInfaq = Infaq::where('status', 'terverifikasi')
            ->orderBy('created_at', 'desc')->take(5)->get()
            ->map(function ($item) {
                return [
                    'type' => 'infaq',
                    'judul' => 'Penerimaan Infaq Digital',
                    'nominal' => $item->nominal,
                    'waktu' => $item->created_at,
                ];
            });

        $latestKeluar = MesjidExpenditure::orderBy('created_at', 'desc')->take(5)->get()
            ->map(function ($item) {
                return [
                    'type' => 'pengeluaran',
                    'judul' => $item->judul,
                    'nominal' => $item->nominal,
                    'waktu' => $item->created_at, // Diambil dari created_at agar presisi
                ];
            });

        // Gabungkan keduanya, urutkan dari yang paling baru, potong jadi 5 item saja
        $aktivitas = $latestInfaq->concat($latestKeluar)
            ->sortByDesc('waktu')
            ->take(5);

        return view('mesjid.dashboard', compact(
            'saldoAktual', 'masukBulanIni', 'keluarBulanIni',
            'labelHari', 'dataMasuk', 'dataKeluar', 'aktivitas'
        ));
    }
}