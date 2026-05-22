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
    $users = \App\Models\User::when($request->search, function ($query, $search) {
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
            'target_user_id' => 'required|exists:users,id'
        ]);

        if ($request->target_user_id != auth()->id() && !in_array(auth()->user()->role, ['superadmin', 'rt'])) {
            abort(403);
        }

        \App\Models\FamilyMember::create([
            'user_id'         => $request->target_user_id,
            'nama'            => $request->nama,
            'status_hubungan' => $request->status_hubungan,
            'nik'             => $request->nik,
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
        ]);

        $family = \App\Models\FamilyMember::findOrFail($id);
        
        // Cek Keamanan
        if ($family->user_id != auth()->id() && !in_array(auth()->user()->role, ['superadmin', 'rt'])) {
            abort(403);
        }

        $family->update([
            'nama'            => $request->nama,
            'status_hubungan' => $request->status_hubungan,
            'nik'             => $request->nik,
        ]);

        return back()->with('success', 'Data Anggota Keluarga berhasil diperbarui!');
    }

    public function updateProfileWarga(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'no_rumah'       => 'nullable|string|max:50',
            'no_kk'          => 'nullable|string|max:30',
            'nik'            => 'nullable|string|max:30',
        ]);

        // 2. Cek Otorisasi: warga hanya boleh update profil dirinya sendiri
        $targetId = $request->target_user_id;
        if ($targetId != auth()->id() && !in_array(auth()->user()->role, ['superadmin', 'rt'])) {
            abort(403, 'Anda tidak diizinkan mengubah data warga lain.');
        }

        // 3. Ambil User target
        $user = User::findOrFail($targetId);

        // 4. Update data ke tabel USERS
        $user->update([
            'no_rumah' => strtoupper($request->no_rumah), // Biar otomatis huruf kapital
            'no_kk'    => $request->no_kk,
            'nik'      => $request->nik,
        ]);

        return back()->with('success', 'Data Identitas Keluarga Berhasil Diperbarui!');
    }
}