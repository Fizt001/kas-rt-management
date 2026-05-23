<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\WargaImport;

class UserController extends Controller
{
    public function index(Request $request)
{
    // Filter berdasarkan nama jika ada input search
    $users = \App\Models\User::with('familyMembers')
        ->when($request->search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('role', 'asc')
        ->orderBy('name', 'asc')
        ->paginate(15) // Batasi 15 data per halaman
        ->withQueryString(); // Menjaga parameter ?search saat pindah halaman

    return view('superadmin.users.index', compact('users'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:superadmin,rt,bendahara,warga,mesjid,koperasi',
            'foto' => 'nullable|image|max:2048'
        ]);

        $restrictedRoles = ['superadmin', 'rt', 'bendahara'];
        if (in_array($request->role, $restrictedRoles) && auth()->user()->role !== 'superadmin') {
            abort(403, 'Hanya Superadmin yang dapat membuat role ini.');
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('warga', 'public');
        }

        User::create($data);
        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required',
            'password' => 'nullable|min:8',
        ]);

        $restrictedRoles = ['superadmin', 'rt', 'bendahara'];
        if (auth()->user()->role !== 'superadmin') {
            if (in_array($user->role, $restrictedRoles)) {
                abort(403, 'Anda tidak dapat memodifikasi akun administrator.');
            }
            if (in_array($request->role, $restrictedRoles)) {
                abort(403, 'Anda tidak dapat memberikan akses administrator.');
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return back()->with('success', 'Data user diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        $restrictedRoles = ['superadmin', 'rt', 'bendahara'];
        if (in_array($user->role, $restrictedRoles) && auth()->user()->role !== 'superadmin') {
            abort(403, 'Anda tidak dapat menghapus akun administrator.');
        }
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }
        $user->delete();
        return back()->with('success', 'User dihapus permanen!');
    }

    public function downloadTemplate()
    {
        $headers = [['nama', 'email', 'password', 'role', 'nama_file_foto']];
        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $data;
            public function __construct($data) { $this->data = $data; }
            public function array(): array { return $this->data; }
        }, 'template_kas_rt.xlsx');
    }

    public function import(Request $request) 
    {
        set_time_limit(0);
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls']);
        Excel::import(new WargaImport, $request->file('file_excel'));
        return back()->with('success', 'Bulk import sukses!');
    }

   // 1. Menampilkan Halaman Profil (Dengan Dropdown Admin)
    public function profileWarga(\Illuminate\Http\Request $request)
    {
        $currentUser = auth()->user();
        $isAdmin = in_array($currentUser->role, ['superadmin', 'rt']);
        
        $targetUserId = $currentUser->id;
        $daftarWarga = collect();

        if ($isAdmin) {
            $daftarWarga = \App\Models\User::where('role', 'warga')->get();
            if ($request->filled('warga_id')) {
                $targetUserId = $request->warga_id;
            }
        }

        $targetUser = \App\Models\User::with('familyMembers')->findOrFail($targetUserId);

        return view('warga.profile', compact('targetUser', 'isAdmin', 'daftarWarga'));
    }
    
    // 3. Tambah Anggota Keluarga
    public function storeFamily(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'status_hubungan' => 'required|string',
            'nik' => 'nullable|string|max:20',
            'kelompok_kk_select' => 'required|string|max:255',
            'kelompok_kk_new' => 'nullable|string|max:255',
            'no_kk_kelompok' => 'nullable|string|max:30',
            'tanggal_lahir' => 'nullable|date',
            'target_user_id' => 'required|exists:users,id'
        ]);

        if ($request->target_user_id != auth()->id() && !in_array(auth()->user()->role, ['superadmin', 'rt'])) {
            abort(403);
        }

        $kelompokKk = $request->kelompok_kk_select === 'NEW' ? $request->kelompok_kk_new : $request->kelompok_kk_select;

        \App\Models\FamilyMember::create([
            'user_id'         => $request->target_user_id,
            'nama'            => $request->nama,
            'status_hubungan' => $request->status_hubungan,
            'nik'             => $request->nik,
            'kelompok_kk'     => $kelompokKk ?: 'KK Utama',
            'no_kk_kelompok'  => $request->no_kk_kelompok,
            'tanggal_lahir'   => $request->tanggal_lahir,
        ]);

        return back()->with('success', 'Anggota Keluarga berhasil ditambahkan!');
    }

    // 4. Hapus Anggota Keluarga
    public function destroyFamily($id)
    {
        $family = \App\Models\FamilyMember::findOrFail($id);
        
        if ($family->user_id != auth()->id() && !in_array(auth()->user()->role, ['superadmin', 'rt'])) {
            abort(403);
        }

        $family->delete();
        return back()->with('success', 'Data Anggota Keluarga dihapus!');
    }

    // Update Data Anggota Keluarga
    public function updateFamily(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'status_hubungan' => 'required|string',
            'nik' => 'nullable|string|max:20',
            'kelompok_kk_select' => 'required|string|max:255',
            'kelompok_kk_new' => 'nullable|string|max:255',
            'no_kk_kelompok' => 'nullable|string|max:30',
            'tanggal_lahir' => 'nullable|date',
        ]);

        $family = \App\Models\FamilyMember::findOrFail($id);
        
        // Cek Keamanan
        if ($family->user_id != auth()->id() && !in_array(auth()->user()->role, ['superadmin', 'rt'])) {
            abort(403);
        }

        $kelompokKk = $request->kelompok_kk_select === 'NEW' ? $request->kelompok_kk_new : $request->kelompok_kk_select;

        $family->update([
            'nama'            => $request->nama,
            'status_hubungan' => $request->status_hubungan,
            'nik'             => $request->nik,
            'kelompok_kk'     => $kelompokKk ?: 'KK Utama',
            'no_kk_kelompok'  => $request->no_kk_kelompok,
            'tanggal_lahir'   => $request->tanggal_lahir,
        ]);

        return back()->with('success', 'Data Anggota Keluarga berhasil diperbarui!');
    }

    public function updateProfileWarga(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'blok_rumah'     => 'nullable|string|max:10',
            'no_rumah'       => 'nullable|string|max:50',
            'no_kk'          => 'nullable|string|max:30',
            'nik'            => 'nullable|string|max:30',
            'tanggal_lahir'  => 'nullable|date',
        ]);

        // Hilangkan spasi agar formatnya benar-benar standar (misal "E 1" jadi "E1")
        $blokRumah = str_replace(' ', '', strtoupper($request->blok_rumah));
        $noRumah = str_replace(' ', '', strtoupper($request->no_rumah));

        // Validasi Unique Kombinasi Blok + No Rumah
        if ($blokRumah && $noRumah) {
            $existingUser = User::where('blok_rumah', $blokRumah)
                                ->where('no_rumah', $noRumah)
                                ->where('id', '!=', $request->target_user_id)
                                ->where('role', 'warga')
                                ->first();
            
            if ($existingUser) {
                return back()->with('error', "Gagal! Rumah dengan Blok {$blokRumah} / No. {$noRumah} sudah diklaim oleh warga lain.");
            }
        }

        // 2. Cek Otorisasi: warga hanya boleh update profil dirinya sendiri
        $targetId = $request->target_user_id;
        if ($targetId != auth()->id() && !in_array(auth()->user()->role, ['superadmin', 'rt'])) {
            abort(403, 'Anda tidak diizinkan mengubah data warga lain.');
        }

        // 3. Ambil User target
        $user = User::findOrFail($targetId);

        // 4. Update data ke tabel USERS
        $user->update([
            'blok_rumah' => $blokRumah,
            'no_rumah' => $noRumah,
            'no_kk'    => $request->no_kk,
            'nik'      => $request->nik,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return back()->with('success', 'Data Identitas Keluarga Berhasil Diperbarui!');
    }
}