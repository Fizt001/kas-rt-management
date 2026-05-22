<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;
use Carbon\Carbon;

class AgendaController extends Controller
{
    /**
     * Menampilkan daftar agenda.
     */
    public function index()
    {
        $agendas = Agenda::latest()->paginate(10);
        
        // Ambil agenda yang lewat hari ini & masih aktif
        $expiredAgendas = Agenda::where('tanggal', '<', now()->format('Y-m-d'))
                                ->where('status', 'aktif')
                                ->get();

        // Pastikan path-nya superadmin.agendas.index atau agendas.index sesuai foldermu
        return view('superadmin.agendas.index', compact('agendas', 'expiredAgendas'));
    }

    /**
     * Menampilkan daftar agenda untuk warga (read-only).
     */
    public function wargaIndex()
    {
        // Pisahkan agenda yang akan datang/aktif dan yang sudah selesai
        $agendasAktif = Agenda::where('status', 'aktif')
            ->orderBy('tanggal', 'asc')
            ->get();
            
        $agendasSelesai = Agenda::where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->take(5) // Tampilkan 5 terakhir saja untuk riwayat
            ->get();

        return view('warga.agendas.index', compact('agendasAktif', 'agendasSelesai'));
    }

    /**
     * Update status agenda (Selesai).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:selesai,aktif']);

        $agenda = Agenda::findOrFail($id);
        $agenda->update(['status' => $request->status]);

        return back()->with('success', 'Agenda berhasil diselesaikan!');
    }

    /**
     * Menunda agenda (Reschedule).
     */
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'tanggal_baru' => 'required|date|after_or_equal:today',
        ]);

        $agenda = Agenda::findOrFail($id);
        $agenda->update([
            'tanggal' => $request->tanggal_baru,
            'status'  => 'aktif'
        ]);

        return back()->with('success', 'Jadwal agenda berhasil diperbarui!');
    }

    /**
     * Menyimpan data agenda baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal'   => 'required|date',
            'waktu'     => 'nullable',
            'lokasi'    => 'nullable|string|max:255',
            'status'    => 'required|in:aktif,selesai',
        ]);

        Agenda::create([
            'user_id'   => auth()->id(),
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal'   => $request->tanggal,
            'waktu'     => $request->waktu,
            'lokasi'    => $request->lokasi,
            'status'    => $request->status,
        ]);

        return back()->with('success', 'Agenda berhasil diposting!');
    }

    /**
     * Mengupdate data agenda.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal'   => 'required|date',
            'waktu'     => 'nullable',
            'lokasi'    => 'nullable|string|max:255',
            'status'    => 'required|in:aktif,selesai',
        ]);

        $agenda = Agenda::findOrFail($id);
        // Gunakan only() bukan all() agar tidak ada field asing yang masuk
        $agenda->update($request->only(['judul', 'deskripsi', 'tanggal', 'waktu', 'lokasi', 'status']));

        return back()->with('success', 'Data agenda diperbarui!');
    }

    /**
     * Menghapus data agenda.
     */
    public function destroy(string $id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();

        return back()->with('success', 'Agenda dihapus!');
    }
}