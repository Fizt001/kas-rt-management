<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MesjidExpenditure;
use Illuminate\Support\Facades\Storage;

class MesjidExpenditureController extends Controller
{
    public function index()
    {
        $pengeluaran = MesjidExpenditure::orderBy('tanggal', 'desc')->get();
        $totalPengeluaran = $pengeluaran->sum('nominal');
        
        return view('mesjid.pengeluaran.index', compact('pengeluaran', 'totalPengeluaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:100',
            'kategori' => 'required|string',
            'bukti_nota' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('bukti_nota');

        if ($request->hasFile('bukti_nota')) {
            $data['bukti_nota'] = $request->file('bukti_nota')->store('mesjid_nota', 'public');
        }

        MesjidExpenditure::create($data);

        return back()->with('success', 'Catatan pengeluaran dana Mesjid berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $expenditure = MesjidExpenditure::findOrFail($id);
        
        if ($expenditure->bukti_nota) {
            Storage::disk('public')->delete($expenditure->bukti_nota);
        }
        
        $expenditure->delete();

        return back()->with('success', 'Catatan pengeluaran berhasil dihapus!');
    }
}