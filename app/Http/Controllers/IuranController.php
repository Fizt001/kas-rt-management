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
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Validasi: Portal pencegah generate tagihan masa depan
        if ($tahunSelected > $currentYear || ($tahunSelected == $currentYear && $bulanSelected > $currentMonth)) {
            return back()->with('error', 'Gagal! Tidak dapat membuat tagihan untuk bulan yang belum berjalan (masa depan).');
        }
        
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

        if ($tagihan->user_id !== auth()->id() && auth()->user()->role === 'warga') {
            abort(403, 'Unauthorized action. Anda tidak dapat membayar tagihan warga lain.');
        }

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
    

}