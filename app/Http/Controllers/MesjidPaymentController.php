<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MesjidPayment;
use Illuminate\Support\Facades\Storage;

class MesjidPaymentController extends Controller
{
    public function index()
    {
        // Ambil data pertama (karena kita pakai single form)
        $setting = MesjidPayment::first(); 
        return view('mesjid.payment.index', compact('setting'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_bank' => 'required',
        'nomor_rekening' => 'required',
        'atas_nama' => 'required',
        'qris_image' => 'nullable|image|max:2048'
    ]);

    // Menggunakan model MesjidPayment (pastikan nama modelnya sesuai dengan tabelmu)
    // Kita ambil data pertama karena ini adalah data pengaturan/setting
    $setting = \App\Models\MesjidPayment::first() ?? new \App\Models\MesjidPayment;

    $setting->nama_bank = $request->nama_bank;
    $setting->nomor_rekening = $request->nomor_rekening;
    $setting->atas_nama = $request->atas_nama;

    if ($request->hasFile('qris_image')) {
        $path = $request->file('qris_image')->store('mesjid', 'public');
        $setting->qris_image = $path;
    }

    $setting->save();

    return back()->with('success', 'Metode Pembayaran Mesjid Berhasil Diperbarui!');
}
}