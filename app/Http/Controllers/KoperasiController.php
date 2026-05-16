<?php

namespace App\Http\Controllers;

use App\Models\KoperasiAccount;
use App\Models\KoperasiTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KoperasiController extends Controller
{
    public function verify(Request $request, $id)
    {
        $transaction = KoperasiTransaction::findOrFail($id);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // Ubah status transaksi menjadi disetujui
            $transaction->update(['status' => 'approved']);

            // Ambil akun koperasi warga
            $akun = KoperasiAccount::firstOrCreate(
                ['user_id' => $transaction->user_id],
                ['saldo_pokok' => 0, 'saldo_wajib' => 0, 'saldo_sukarela' => 0, 'total_saldo' => 0]
            );

            // LOGIKA 1: JIKA INI SETORAN (Uang Masuk)
            if ($transaction->type === 'setoran') {
                if ($transaction->kategori === 'pokok') $akun->saldo_pokok += $transaction->amount;
                elseif ($transaction->kategori === 'wajib') $akun->saldo_wajib += $transaction->amount;
                elseif ($transaction->kategori === 'sukarela') $akun->saldo_sukarela += $transaction->amount;
            } 
            // LOGIKA 2: JIKA INI PENARIKAN (Uang Keluar)
            elseif ($transaction->type === 'penarikan') {
                // Pastikan saldo sukarela tidak minus!
                if ($akun->saldo_sukarela < $transaction->amount) {
                    throw new \Exception('Saldo sukarela warga tidak mencukupi untuk penarikan ini.');
                }
                $akun->saldo_sukarela -= $transaction->amount;
            }

            // Hitung ulang total saldo keseluruhan
            $akun->total_saldo = $akun->saldo_pokok + $akun->saldo_wajib + $akun->saldo_sukarela;
            $akun->save();

            DB::commit();

            $pesan = $transaction->type === 'setoran' 
                     ? 'Setoran berhasil diverifikasi. Saldo bertambah!' 
                     : 'Penarikan disetujui. Saldo warga telah dipotong.';

            return back()->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Memproses Penolakan Setoran/Penarikan Warga
     */
    public function reject(Request $request, $id)
    {
        $transaction = KoperasiTransaction::findOrFail($id);

        // Pastikan statusnya masih pending
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        // Ubah status menjadi ditolak (tanpa mengubah saldo warga)
        $transaction->update(['status' => 'rejected']);

        return back()->with('success', 'Transaksi berhasil ditolak. Saldo warga tidak berubah.');
    }

    /**
     * Menampilkan semua daftar transaksi koperasi
     */
    public function transaksi()
    {
        // Ambil semua transaksi, urutkan dari yang terbaru
        $transactions = KoperasiTransaction::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Kita pakai pagination biar rapi kalau datanya ribuan

        return view('koperasi.transaksi.index', compact('transactions'));
    }

    /**
     * Menampilkan daftar anggota koperasi beserta rincian saldonya
     */
    public function anggota()
    {
        // Ambil semua akun koperasi beserta data usernya
        // Kita urutkan berdasarkan warga yang saldonya paling banyak
        $accounts = KoperasiAccount::with('user')
            ->orderBy('total_saldo', 'desc')
            ->paginate(15);

        return view('koperasi.anggota.index', compact('accounts'));
    }

    /**
     * Menampilkan daftar semua kasbon/pinjaman warga
     */
    public function kasbon()
    {
        // Ambil semua data pinjaman, urutkan dari yang terbaru
        $loans = \App\Models\KoperasiLoan::with('user', 'installments')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('koperasi.kasbon.index', compact('loans'));
    }

    /**
     * Memproses Persetujuan Kasbon & Membuat Jadwal Cicilan Otomatis
     */
    public function approveKasbon($id)
    {
        $loan = \App\Models\KoperasiLoan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Pengajuan kasbon ini sudah diproses sebelumnya.');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Ubah status pengajuan menjadi disetujui
            $loan->update([
                'status'      => 'approved',
                'approved_at' => now()
            ]);

            // 2. Hitung nominal cicilan per bulan (Dibulatkan ke atas agar tidak ada desimal)
            $cicilanPerBulan = ceil($loan->amount / $loan->tenor);

            // 3. Generate tagihan (installments) sebanyak jumlah tenor bulan
            for ($i = 1; $i <= $loan->tenor; $i++) {
                \App\Models\KoperasiLoanInstallment::create([
                    'koperasi_loan_id' => $loan->id,
                    'cicilan_ke'       => $i,
                    'amount'           => $cicilanPerBulan,
                    'jatuh_tempo'      => now()->addMonths($i)->format('Y-m-d'), // Jatuh tempo bulan depannya
                    'status'           => 'belum_bayar'
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return back()->with('success', 'Pinjaman disetujui! Sistem telah membuat jadwal cicilan otomatis untuk warga tersebut.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memproses pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Memproses Verifikasi Pembayaran Cicilan
     */
    public function verifyCicilan(Request $request, $id)
    {
        $cicilan = \App\Models\KoperasiLoanInstallment::findOrFail($id);

        if ($cicilan->status !== 'pending') {
            return back()->with('error', 'Cicilan ini sudah diproses sebelumnya.');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Ubah status cicilan jadi lunas
            $cicilan->update([
                'status'  => 'lunas',
                'paid_at' => now(),
            ]);

            // 2. Cek apakah ini cicilan terakhir? Kalau iya, lunaskan status Induk Pinjamannya!
            $loan = $cicilan->loan;
            $sisaBelumLunas = $loan->installments()->where('status', '!=', 'lunas')->count();

            if ($sisaBelumLunas === 0) {
                $loan->update(['status' => 'lunas']);
            }

            \Illuminate\Support\Facades\DB::commit();

            return back()->with('success', 'Pembayaran cicilan berhasil diverifikasi! Uang kembali masuk ke Kas Tersedia.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memproses verifikasi cicilan: ' . $e->getMessage());
        }
    }

    /**
     * Menolak Pembayaran Cicilan
     */
    public function rejectCicilan(Request $request, $id)
    {
        $cicilan = \App\Models\KoperasiLoanInstallment::findOrFail($id);

        // Kembalikan status ke belum_bayar agar warga bisa upload struk ulang
        $cicilan->update([
            'status' => 'belum_bayar'
        ]);

        return back()->with('success', 'Pembayaran cicilan ditolak. Warga harus mengunggah ulang bukti transfer.');
    }
}