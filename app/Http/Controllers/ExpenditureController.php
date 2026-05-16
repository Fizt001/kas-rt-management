<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda; 
use Carbon\Carbon;

class ExpenditureController extends Controller
{
    public function index()
    {
        // Ambil semua agenda dari yang terbaru
        $agendas = Agenda::orderBy('tanggal', 'desc')->get();
        return view('rt.expenditure.index', compact('agendas'));
    }

    // Fungsi 1: Khusus Upload Bukti Foto Acara
    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'bukti_kegiatan' => 'required|image|max:2048',
        ]);

        $agenda = Agenda::findOrFail($id);

        if (Carbon::parse($agenda->tanggal)->startOfDay()->isFuture()) {
            return back()->with('error', 'Gagal! Belum waktunya untuk upload laporan kegiatan ini.');
        }

        $path = $request->file('bukti_kegiatan')->store('pengeluaran', 'public');
        $agenda->update(['bukti_kegiatan' => $path]);

        return back()->with('success', 'Foto dokumentasi kegiatan berhasil diunggah!');
    }

    // Fungsi 2: Khusus Upload Nota & Nominal Pengeluaran
    public function uploadNota(Request $request, $id)
    {
        $request->validate([
            'realisasi_dana' => 'required|numeric|min:0',
            'nota_belanja'   => 'nullable|image|max:2048',
        ]);

        $agenda = Agenda::findOrFail($id);

        if (Carbon::parse($agenda->tanggal)->startOfDay()->isFuture()) {
            return back()->with('error', 'Gagal! Belum waktunya untuk upload laporan ini.');
        }

        $updateData = ['realisasi_dana' => $request->realisasi_dana];

        if ($request->hasFile('nota_belanja')) {
            $updateData['nota_belanja'] = $request->file('nota_belanja')->store('pengeluaran', 'public');
        }

        $agenda->update($updateData);

        return back()->with('success', 'Nota belanja & anggaran kas berhasil disimpan!');
    }
}