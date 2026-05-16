<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Billing;
use Carbon\Carbon;

class TagihanWargaController extends Controller
{
    public function index(Request $request)
    {
        $bulanIni = $request->bulan ?? \Carbon\Carbon::now()->translatedFormat('F');
        $tahunIni = $request->tahun ?? \Carbon\Carbon::now()->year;

        // 1. Ubah get() menjadi paginate(11)
        // 2. Gunakan through() untuk memetakan data tanpa merusak struktur Paginator
        $rekap = User::where('role', 'warga')
            ->orderBy('name', 'asc')
            ->paginate(14)
            ->through(function ($u) use ($bulanIni, $tahunIni) {
                
                $billingBulanIni = \App\Models\Billing::where('user_id', $u->id)
                    ->where('bulan', $bulanIni)
                    ->where('tahun', $tahunIni)
                    ->first();

                $totalTunggakan = \App\Models\Billing::where('user_id', $u->id)
                    ->whereIn('status', ['belum_lunas', 'pending'])
                    ->sum('total_amount');

                return [
                    'id' => $u->id,
                    'nama' => $u->name,
                    'no_rumah' => $u->no_rumah ?? '-', 
                    'status_sekarang' => $billingBulanIni->status ?? 'belum_ada',
                    'total_tunggakan' => $totalTunggakan,
                    'wa_phone' => $u->phone,
                ];
            });

        // 3. Tambahkan appends agar filter Bulan & Tahun tidak hilang saat klik halaman 2, 3, dst.
        $rekap->appends($request->all());

        return view('rt.tagihan.index', compact('rekap', 'bulanIni', 'tahunIni'));
    }
}