<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Billing;
use Carbon\Carbon;

class TagihanWargaController extends Controller
{
    /**
     * Konversi nama bulan Indonesia/Inggris atau string angka ke integer 1-12
     */
    private function parseBulan($bulan): int
    {
        if (is_numeric($bulan)) {
            return (int) $bulan;
        }
        $map = [
            'januari'=>1,'februari'=>2,'maret'=>3,'april'=>4,'mei'=>5,'juni'=>6,
            'juli'=>7,'agustus'=>8,'september'=>9,'oktober'=>10,'november'=>11,'desember'=>12,
            'january'=>1,'february'=>2,'march'=>3,'may'=>5,'june'=>6,'july'=>7,
            'august'=>8,'september'=>9,'october'=>10,'november'=>11,'december'=>12,
        ];
        return $map[strtolower(trim($bulan))] ?? Carbon::now()->month;
    }

    public function index(Request $request)
    {
        // Konversi bulan dari view (nama string) ke integer untuk query
        $bulanInt  = $this->parseBulan($request->bulan ?? Carbon::now()->month);
        $tahunIni  = (int) ($request->tahun ?? Carbon::now()->year);
        $search    = $request->search ?? '';

        // $bulanIni dikirim ke view sebagai integer agar dropdown bisa highlight bulan yang aktif
        $bulanIni  = $bulanInt;

        $rekap = User::where('role', 'warga')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('no_rumah', 'like', "%{$search}%");
                });
            })
            ->with(['billings' => function($q) use ($bulanInt, $tahunIni) {
                $q->where('bulan', $bulanInt)->where('tahun', $tahunIni);
            }])
            ->withSum(['billings as total_tunggakan' => function($q) {
                $q->whereIn('status', ['belum_lunas', 'pending']);
            }], 'total_amount')
            ->withCount(['billings as jumlah_bulan_tunggakan' => function($q) {
                $q->whereIn('status', ['belum_lunas', 'pending']);
            }])
            ->orderBy('name', 'asc')
            ->paginate(14)
            ->through(function ($u) {
                $billingBulanIni = $u->billings->first();
                return [
                    'id'              => $u->id,
                    'nama'            => $u->name,
                    'blok'            => $u->blok_rumah ?? '-',
                    'no_rumah'        => $u->no_rumah ?? '-', 
                    'status_sekarang' => $billingBulanIni->status ?? 'belum_ada',
                    'total_tunggakan' => $u->total_tunggakan ?? 0,
                    'jumlah_bulan_tunggakan' => $u->jumlah_bulan_tunggakan ?? 0,
                    'wa_phone'        => $u->phone,
                ];
            });

        $rekap->appends($request->all());

        return view('rt.tagihan.index', compact('rekap', 'bulanIni', 'tahunIni'));
    }
}