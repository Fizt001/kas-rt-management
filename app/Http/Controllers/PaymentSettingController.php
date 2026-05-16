<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $setting = PaymentSetting::first(); // Ambil data pertama
        return view('rt.settings.payment', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string',
            'nomor_rekening' => 'required|string',
            'nama_penerima' => 'required|string',
            'qris_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $setting = PaymentSetting::first() ?? new PaymentSetting();
        $setting->nama_bank = $request->nama_bank;
        $setting->nomor_rekening = $request->nomor_rekening;
        $setting->nama_penerima = $request->nama_penerima;

        if ($request->hasFile('qris_image')) {
            // Hapus foto lama jika ada
            if ($setting->qris_path) {
                Storage::disk('public')->delete($setting->qris_path);
            }
            $path = $request->file('qris_image')->store('qris', 'public');
            $setting->qris_path = $path;
        }

        $setting->save();

        return back()->with('success', 'Metode pembayaran berhasil diperbarui!');
    }
}