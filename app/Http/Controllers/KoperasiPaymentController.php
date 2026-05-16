<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KoperasiPaymentController extends Controller
{
    public function index() {
    $setting = \App\Models\KoperasiPayment::first();
    return view('koperasi.payment.index', compact('setting'));
}

public function store(Request $request) {
    $setting = \App\Models\KoperasiPayment::first() ?? new \App\Models\KoperasiPayment;
    $setting->nama_bank = $request->nama_bank;
    $setting->nomor_rekening = $request->nomor_rekening;
    $setting->atas_nama = $request->atas_nama;

    if ($request->hasFile('qris_image')) {
        $setting->qris_image = $request->file('qris_image')->store('koperasi', 'public');
    }
    $setting->save();
    return back()->with('success', 'Rekening Koperasi diperbarui!');
}
}
