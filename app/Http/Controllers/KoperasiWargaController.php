<?php

namespace App\Http\Controllers;

use App\Models\KoperasiAccount;
use App\Models\KoperasiTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoperasiWargaController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $role = strtolower(str_replace(' ', '', $user->role ?? 'warga'));
    $isAdmin = in_array($role, ['superadmin', 'rt']);

    // AMBIL DAFTAR WARGA: Hanya yang sudah punya akun koperasi
    $daftarWarga = $isAdmin 
    ? \App\Models\User::where('role', 'warga')
        ->whereIn('id', \App\Models\KoperasiAccount::pluck('user_id')) // Ambil ID yang ada di tabel koperasi saja
        ->orderBy('name', 'asc')
        ->get() 
    : collect();

    $targetUserId = $request->get('warga_id', $user->id);
    $targetUser = \App\Models\User::find($targetUserId) ?? $user;

    $akun = \App\Models\KoperasiAccount::where('user_id', $targetUser->id)->first();
    $riwayat = \App\Models\KoperasiTransaction::where('user_id', $targetUser->id)->latest()->get();
    
    $pinjamanAktif = \App\Models\KoperasiLoan::with('installments')
        ->where('user_id', $targetUser->id)
        ->whereIn('status', ['pending', 'approved'])
        ->first();

    $setting = \App\Models\KoperasiPayment::first();

    return view('warga.koperasi.index', compact(
        'akun', 'riwayat', 'pinjamanAktif', 'setting', 'isAdmin', 'targetUser', 'daftarWarga'
    ));
}

    /**
     * Memproses persetujuan bergabung dengan Koperasi
     */
    public function join(Request $request)
    {
        $user = Auth::user();

        // Buat akun koperasi dengan saldo awal 0
        KoperasiAccount::create([
            'user_id' => $user->id,
            'saldo_pokok' => 0,
            'saldo_wajib' => 0,
            'saldo_sukarela' => 0,
            'total_saldo' => 0
        ]);

        return redirect()->route('warga.koperasi')->with('success', 'Selamat! Anda resmi bergabung dengan Koperasi RT. Silakan lakukan setoran Simpanan Pokok pertama Anda.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:pokok,wajib,sukarela',
            'amount' => 'required|numeric|min:10000',
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        $path = $request->file('bukti_transfer')->store('koperasi/bukti', 'public');

        KoperasiTransaction::create([
            'user_id' => Auth::id(),
            'type' => 'setoran',
            'kategori' => $request->kategori,
            'amount' => $request->amount,
            'status' => 'pending',
            'bukti_transfer' => $path,
        ]);

        return back()->with('success', 'Setoran berhasil diajukan dan sedang menunggu verifikasi Pengurus.');
    }

    /**
     * Memproses pengajuan penarikan dana (Hanya dari Saldo Sukarela)
     */
    public function tarikDana(Request $request)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:10000',
            'bank_tujuan' => 'required|string|max:255', // Rekening tujuan transfer
        ]);

        $user = Auth::user();
        $akun = KoperasiAccount::where('user_id', $user->id)->first();

        // Validasi: Apakah saldonya cukup?
        if (!$akun || $akun->saldo_sukarela < $request->amount) {
            return back()->with('error', 'Maaf, Saldo Sukarela Anda tidak mencukupi untuk melakukan penarikan sebesar ini.');
        }

        // Catat pengajuan penarikan ke database
        KoperasiTransaction::create([
            'user_id'    => $user->id,
            'type'       => 'penarikan',
            'kategori'   => 'sukarela',
            'amount'     => $request->amount,
            'keterangan' => 'Transfer ke: ' . $request->bank_tujuan,
            'status'     => 'pending',
            // Tidak perlu bukti transfer karena warga yang meminta uang
        ]);

        return back()->with('success', 'Pengajuan penarikan dana berhasil dikirim. Silakan tunggu Admin mentransfer dana ke rekening Anda.');
    }

    public function ajukanPinjaman(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50000',
            'tenor'  => 'required|integer|min:1|max:12', // Maksimal cicil 12 bulan
            'alasan' => 'required|string|max:255',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();

        // Validasi Ekstra: Pastikan tidak ada pinjaman yang belum lunas
        $punyaUtang = \App\Models\KoperasiLoan::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($punyaUtang) {
            return back()->with('error', 'Anda masih memiliki pengajuan atau pinjaman aktif yang belum lunas.');
        }

        // Catat pengajuan baru
        \App\Models\KoperasiLoan::create([
            'user_id' => $user->id,
            'amount'  => $request->amount,
            'tenor'   => $request->tenor,
            'alasan'  => $request->alasan,
            'status'  => 'pending',
        ]);

        return back()->with('success', 'Pengajuan kasbon berhasil dikirim! Silakan tunggu konfirmasi dari Pengurus Koperasi.');
    }

    /**
     * Memproses pembayaran cicilan kasbon warga
     */
    public function bayarCicilan(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $cicilan = \App\Models\KoperasiLoanInstallment::findOrFail($id);

        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $namaFile = time() . '_cicilan_' . $file->getClientOriginalName();
            $path = $file->storeAs('bukti_transfer', $namaFile, 'public');
            
            $cicilan->update([
                'bukti_transfer' => $path, 
                'status'         => 'pending'      
            ]);
        }

        return back()->with('success', 'Bukti pembayaran cicilan berhasil dikirim! Silakan tunggu verifikasi pengurus.');
    }
}