<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infaq;

class InfaqController extends Controller
{
   public function index(Request $request)
{
    $user = auth()->user();
    $role = strtolower(str_replace(' ', '', $user->role));
    $isAdmin = in_array($role, ['superadmin', 'rt']);

    // Ambil daftar warga murni
    $daftarWarga = $isAdmin ? \App\Models\User::where('role', 'warga')->orderBy('name')->get() : collect();

    // Default target: jika admin, ambil warga pertama dari list (jika tidak ada id di request)
    $targetUserId = $request->get('warga_id');
    
    if ($isAdmin && !$targetUserId) {
        $targetUser = $daftarWarga->first() ?? $user;
    } else {
        $targetUser = \App\Models\User::find($targetUserId) ?? $user;
    }

    // 2. Ambil Riwayat Infaq
    $infaqs = \App\Models\Infaq::where('user_id', $targetUser->id)
                ->latest()
                ->get();

    // 3. Hitung Total Infaq
    $totalInfaqTarget = \App\Models\Infaq::where('user_id', $targetUser->id)
                        ->where('status', 'terverifikasi')
                        ->sum('nominal');

    // 4. Ambil Daftar Warga untuk Dropdown Admin
    $daftarWarga = $isAdmin ? \App\Models\User::where('role', 'warga')->orderBy('name')->get() : collect();

    /**
     * FIX ERROR DI SINI:
     * Ganti 'Setting' dengan nama model yang kamu gunakan untuk simpan nomor rekening/QRIS.
     * Jika kamu menggunakan 'PaymentSetting', ganti jadi: \App\Models\PaymentSetting::first();
     */
    $setting = \App\Models\PaymentSetting::first(); 

    return view('warga.infaq.index', compact(
        'infaqs', 
        'totalInfaqTarget', 
        'isAdmin', 
        'targetUser', 
        'daftarWarga', 
        'setting'
    ));
}
}