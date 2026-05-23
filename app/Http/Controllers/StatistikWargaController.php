<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FamilyMember;
use Carbon\Carbon;

class StatistikWargaController extends Controller
{
    public function index()
    {
        // 1. Dapatkan semua akun Warga (1 rumah = 1 akun warga) beserta anggota keluarganya
        $wargaAccounts = User::where('role', 'warga')->with('familyMembers')->get();

        $rumahStats = [];
        $umurStats = [
            'Balita (0-5)' => 0,
            'Anak-anak (6-12)' => 0,
            'Remaja (13-17)' => 0,
            'Dewasa (18-59)' => 0,
            'Lansia (60+)' => 0,
        ];

        $totalWargaKeseluruhan = 0;
        $totalKK = 0;

        foreach ($wargaAccounts as $warga) {
            // Hitung jumlah individu dalam rumah ini (Akun utama + anggota keluarga)
            $jumlahPenghuni = 1 + $warga->familyMembers->count();
            $totalWargaKeseluruhan += $jumlahPenghuni;

            // Hitung jumlah KK dalam rumah ini
            // Anggap akun utama adalah 1 KK (KK Utama), lalu cek kelompok_kk di anggota keluarga
            $kelompokKKs = $warga->familyMembers->pluck('kelompok_kk')->unique();
            $jumlahKK = $kelompokKKs->count();
            // Jika ada anggota keluarga dengan kelompok KK berbeda dari 'KK Utama', maka tambah.
            // Secara umum, jumlah unik 'kelompok_kk' (termasuk 'KK Utama') merepresentasikan jumlah KK.
            // Pastikan 'KK Utama' juga dihitung jika tidak ada di array unik.
            $kks = $kelompokKKs->toArray();
            if (!in_array('KK Utama', $kks) && !empty($kks)) {
                $jumlahKK += 1;
            } elseif (empty($kks)) {
                $jumlahKK = 1; // Hanya kepala keluarga sendiri
            }
            $totalKK += $jumlahKK;

            $rumahStats[] = [
                'no_rumah' => ($warga->blok_rumah || $warga->no_rumah) ? trim("{$warga->blok_rumah} / {$warga->no_rumah}", ' / ') : 'Belum Diatur',
                'nama_akun' => $warga->name,
                'jumlah_penghuni' => $jumlahPenghuni,
                'jumlah_kk' => $jumlahKK,
            ];

            // Kalkulasi Umur Akun Utama
            $this->hitungGolonganUmur($warga->tanggal_lahir, $umurStats);

            // Kalkulasi Umur Anggota Keluarga
            foreach ($warga->familyMembers as $anggota) {
                $this->hitungGolonganUmur($anggota->tanggal_lahir, $umurStats);
            }
        }

        // Urutkan berdasarkan no_rumah
        usort($rumahStats, function($a, $b) {
            return strcmp($a['no_rumah'], $b['no_rumah']);
        });

        // Persiapkan data untuk chart
        $chartLabels = array_keys($umurStats);
        $chartData = array_values($umurStats);

        return view('rt.statistik.index', compact(
            'rumahStats', 
            'umurStats', 
            'totalWargaKeseluruhan', 
            'totalKK',
            'chartLabels',
            'chartData'
        ));
    }

    private function hitungGolonganUmur($tanggalLahir, &$umurStats)
    {
        if (!$tanggalLahir) {
            return; // Jika tidak ada tanggal lahir, lewatkan
        }

        $umur = Carbon::parse($tanggalLahir)->age;

        if ($umur >= 0 && $umur <= 5) {
            $umurStats['Balita (0-5)']++;
        } elseif ($umur >= 6 && $umur <= 12) {
            $umurStats['Anak-anak (6-12)']++;
        } elseif ($umur >= 13 && $umur <= 17) {
            $umurStats['Remaja (13-17)']++;
        } elseif ($umur >= 18 && $umur <= 59) {
            $umurStats['Dewasa (18-59)']++;
        } elseif ($umur >= 60) {
            $umurStats['Lansia (60+)']++;
        }
    }
}
