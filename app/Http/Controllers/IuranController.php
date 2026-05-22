<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Billing;
use App\Models\IuranMaster;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IuranController extends Controller
{
    public function indexMaster()
    {
        $masters = IuranMaster::all();
        return view('rt.iuran.master', compact('masters'));
    }

    public function storeMaster(Request $request)
    {
        $request->validate([
            'nama_iuran' => 'required|string|max:255',
            'nominal'    => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
        ]);

        IuranMaster::create([
            'nama_iuran' => $request->nama_iuran,
            'nominal'    => $request->nominal,
            'deskripsi'  => $request->deskripsi,
            'is_active'  => true,
        ]);

        return back()->with('success', 'Jenis iuran berhasil ditambahkan!');
    }

    public function updateMaster(Request $request, $id)
    {
        $request->validate([
            'nama_iuran' => 'required|string|max:255',
            'nominal'    => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
        ]);

        $iuran = IuranMaster::findOrFail($id);
        // Gunakan only() bukan all() agar tidak ada field asing yang masuk
        $iuran->update($request->only(['nama_iuran', 'nominal', 'deskripsi', 'is_active']));

        return back()->with('success', 'Jenis iuran berhasil diperbarui!');
    }

    public function destroyMaster($id)
    {
        DB::transaction(function () use ($id) {
            $iuran = IuranMaster::findOrFail($id);
            $iuran->delete();
        });

        return redirect()->back()->with('success', 'Iuran dan riwayat tagihan terkait telah dihapus.');
    }

    public function generateTagihan(Request $request)
    {
        $warga = User::where('role', 'warga')->get();
        $iuranAktif = IuranMaster::where('is_active', true)->get();
        
        if ($iuranAktif->isEmpty()) {
            return back()->with('error', 'Gagal! Aktifkan dulu minimal satu Master Iuran.');
        }

        // Langsung jumlahkan semua nominal iuran yang aktif
        $totalNominal = $iuranAktif->sum('nominal'); 

        // Standardisasi: simpan bulan sebagai INTEGER (1-12) dan tahun sebagai integer
        $bulanSelected = (int) ($request->bulan ?? Carbon::now()->month);
        $tahunSelected = (int) ($request->tahun ?? Carbon::now()->year);
        
        $count = 0;

        foreach ($warga as $w) {
            $exists = Billing::where('user_id', $w->id)
                ->where('bulan', $bulanSelected)
                ->where('tahun', $tahunSelected)
                ->exists();

            if (!$exists) {
                // Cuma buat 1 baris (1 tagihan total) per warga
                Billing::create([
                    'user_id'      => $w->id,
                    'bulan'        => $bulanSelected,
                    'tahun'        => $tahunSelected,
                    'total_amount' => $totalNominal, 
                    'status'       => 'belum_lunas',
                ]);
                $count++;
            }
        }

        return back()->with('success', "Berhasil men-generate $count tagihan total baru.");
    } 
    
    public function wargaIndex(\Illuminate\Http\Request $request)
    {
        // Standardisasi: bulan selalu integer (1-12)
        $tahun = (int) ($request->tahun ?? date('Y'));
        $bulan = (int) ($request->bulan ?? date('n'));
        $currentUser = auth()->user();

        // 1. Cek apakah user punya hak akses pantau (Admin/RT/Bendahara)
        $isAdmin = in_array($currentUser->role, ['superadmin', 'rt', 'bendahara']);
        
        // 2. Tentukan ID siapa yang mau dilihat tagihannya
        $targetUserId = $currentUser->id; // Default: Tagihan diri sendiri
        $daftarWarga = collect();

        if ($isAdmin) {
            // Jika dia admin, ambil semua data warga untuk di dropdown
            $daftarWarga = \App\Models\User::where('role', 'warga')->get();
            
            // Jika admin memilih warga tertentu dari dropdown
            if ($request->filled('warga_id')) {
                $targetUserId = $request->warga_id;
            }
        }

        // 3. Ambil data tagihan sesuai target ID
        $tagihan = \App\Models\Billing::where('user_id', $targetUserId)
                    ->where('tahun', $tahun)
                    ->get();

        // 4. Ambil informasi nama user yang sedang dilihat
        $targetUser = \App\Models\User::find($targetUserId);

        $masters = \App\Models\IuranMaster::where('is_active', true)->get();
        $setting = \Illuminate\Support\Facades\DB::table('payment_settings')->first(); 

        return view('warga.iuran.index', compact('tagihan', 'tahun', 'masters', 'setting', 'isAdmin', 'daftarWarga', 'targetUser'));
    }
    
    public function indexVerifikasi(Request $request)
    {
        $query = Billing::with('user')->where('status', 'pending');

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Tidak perlu di-group lagi karena sudah 1 baris per bulan
        $payments = $query->latest()->get(); 
        $masterIurans = IuranMaster::where('is_active', true)->get();

        return view('rt.iuran.verifikasi', compact('payments', 'masterIurans'));
    }

    public function approvePembayaran($id)
    {
        $billing = Billing::findOrFail($id);
        // Langsung update baris tersebut (tidak perlu pakai where lagi)
        $billing->update([
            'status'      => 'lunas',
            'verified_at' => now(),
            'verified_by' => auth()->id()
        ]);

        return back()->with('success', 'Pembayaran warga berhasil diverifikasi dan dilunaskan!');
    }
    
    public function bayar(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $tagihan = Billing::findOrFail($id);

        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bukti_transfer', $namaFile, 'public');
            
            // Langsung update baris tagihan tersebut
            $tagihan->update([
                'bukti_transfer' => $path, 
                'status'         => 'pending'      
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil dikirim!');
    }
    
    /**
     * Fitur Sultan: Bayar Iuran RT Otomatis pakai Saldo Koperasi
     */
    public function bayarPakaiKoperasi(Request $request, $id)
    {
        // 1. Cari data tagihan (Billing) yang mau dibayar
        $tagihan = \App\Models\Billing::findOrFail($id); 

        $user = \Illuminate\Support\Facades\Auth::user();

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 2. Lock baris saldo agar request bersamaan tidak bisa lolos validasi sekaligus
            $akunKoperasi = \App\Models\KoperasiAccount::where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            // 3. Validasi: Apakah warga punya akun koperasi dan saldo sukarelanya cukup?
            if (!$akunKoperasi || $akunKoperasi->saldo_sukarela < $tagihan->total_amount) {
                \Illuminate\Support\Facades\DB::rollBack();
                return back()->with('error', 'Saldo Sukarela Koperasi Anda tidak mencukupi untuk membayar tagihan ini. (Dibutuhkan: Rp ' . number_format($tagihan->total_amount, 0, ',', '.') . ')');
            }

            // 4. Potong Saldo Sukarela Koperasi
            $akunKoperasi->saldo_sukarela -= $tagihan->total_amount;
            // Update total saldo secara akurat
            $akunKoperasi->total_saldo = $akunKoperasi->saldo_pokok + $akunKoperasi->saldo_wajib + $akunKoperasi->saldo_sukarela;
            $akunKoperasi->save();

            // 5. Catat Mutasi Keluar di Buku Koperasi (Otomatis Approved)
            \App\Models\KoperasiTransaction::create([
                'user_id'        => $user->id,
                'type'           => 'penarikan',
                'kategori'       => 'sukarela',
                'amount'         => $tagihan->total_amount,
                'keterangan'     => 'Pembayaran Otomatis Iuran RT (' . $tagihan->bulan . ' ' . $tagihan->tahun . ')',
                'status'         => 'approved',
            ]);

            // 6. Update Status Tagihan (Billing) menjadi LUNAS seketika
            $tagihan->update([
                'status'         => 'lunas', 
                'verified_at'    => now(),
                'verified_by'    => $user->id, // Warga memverifikasi dirinya sendiri pakai sistem
                'bukti_transfer' => 'PAID_VIA_KOPERASI' // Sebagai penanda buat Admin/Bendahara
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return back()->with('success', 'Luar biasa! Tagihan Iuran RT berhasil dilunasi menggunakan Saldo Koperasi Anda.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}