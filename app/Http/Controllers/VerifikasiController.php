<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing; 
use App\Models\IuranMaster; 

class VerifikasiController extends Controller
{
    /**
     * Konversi nama bulan atau string angka ke integer 1-12
     */
    private function parseBulan($bulan): ?int
    {
        if (empty($bulan)) return null;
        if (is_numeric($bulan)) return (int) $bulan;
        $map = [
            'januari'=>1,'februari'=>2,'maret'=>3,'april'=>4,'mei'=>5,'juni'=>6,
            'juli'=>7,'agustus'=>8,'september'=>9,'oktober'=>10,'november'=>11,'desember'=>12,
        ];
        return $map[strtolower(trim($bulan))] ?? null;
    }

    public function index(Request $request)
    {
        // 1. Ambil data Master Iuran untuk ditampilkan di Card Biru
        $masterIurans = IuranMaster::where('is_active', true)->get();

        // 2. Ambil data tagihan yang butuh diverifikasi
        $query = Billing::with('user')->where('status', 'pending');

        // Fitur Filter Bulan & Tahun (konversi ke integer)
        if ($request->filled('bulan')) {
            $bulanInt = $this->parseBulan($request->bulan);
            if ($bulanInt) {
                $query->where('bulan', $bulanInt);
            }
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', (int) $request->tahun);
        }

        $payments = $query->latest()->get();

        return view('rt.iuran.verifikasi', compact('masterIurans', 'payments'));
    }

    public function approve($id)
    {
        $billing = Billing::findOrFail($id);
        
        $billing->update([
            'status'      => 'lunas',
            'verified_at' => now(),
            'verified_by' => auth()->id()
        ]);

        return back()->with('success', 'Pembayaran warga berhasil disetujui!');
    }
}