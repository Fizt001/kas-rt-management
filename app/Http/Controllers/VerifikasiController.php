<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing; 
use App\Models\IuranMaster; 

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data Master Iuran untuk ditampilkan di Card Biru
        $masterIurans = IuranMaster::where('is_active', true)->get();

        // 2. Ambil data tagihan yang butuh diverifikasi
        $query = Billing::with('user')->where('status', 'pending');

        // Fitur Filter Bulan & Tahun
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // KARENA DATABASE SUDAH 1 BARIS PER BULAN, TIDAK PERLU GROUPING LAGI!
        $payments = $query->latest()->get();

        return view('rt.iuran.verifikasi', compact('masterIurans', 'payments'));
    }

    public function approve($id)
    {
        // Karena tidak ada grouping, ID yang masuk adalah 1 ID tagihan asli
        $billing = Billing::findOrFail($id);
        
        $billing->update([
            'status'      => 'lunas',
            'verified_at' => now(),
            'verified_by' => auth()->id()
        ]);

        return back()->with('success', 'Pembayaran warga berhasil disetujui!');
    }
}