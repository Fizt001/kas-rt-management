<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\ExpenditureController;
use App\Http\Controllers\TagihanWargaController;
use App\Http\Controllers\InfaqController;
use App\Http\Controllers\MesjidPaymentController;
use App\Http\Controllers\MesjidExpenditureController;
use App\Http\Controllers\MesjidLaporanController;
use App\Http\Controllers\KoperasiController;
use App\Http\Controllers\KoperasiPaymentController;
use App\Http\Controllers\KoperasiWargaController;
use App\Http\Controllers\KoperasiLaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Manajemen KAS-RT
|--------------------------------------------------------------------------
*/

// 1. Redirect Root ke Login
Route::get('/', function () 
    {
        return redirect('/login');
    }
);

// 2. Dashboard Multi-role
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    if ($role === 'rt' || request()->routeIs('rt.dashboard')) {
        return app(\App\Http\Controllers\RtDashboardController::class)->index();
    }

    if ($role === 'bendahara' || request()->routeIs('bendahara.dashboard')) {
        return app(\App\Http\Controllers\BendaharaDashboardController::class)->index();
    }

    if ($role === 'warga' || request()->routeIs('warga.dashboard')) {
        return app(\App\Http\Controllers\WargaDashboardController::class)->index();
    }
            
    if ($role === 'mesjid' || request()->routeIs('mesjid.dashboard')) {
        return app(\App\Http\Controllers\MesjidDashboardController::class)->index();
    }

    if ($role === 'koperasi' || request()->routeIs('koperasi.admin.*')) {
        return app(\App\Http\Controllers\KoperasiDashboardController::class)->index();
    }

    if (view()->exists($role . '.dashboard')) {
        return view($role . '.dashboard');
    }
 
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. GRUP AKSES ADMIN (RT, BENDAHARA, & SUPERADMIN)
Route::middleware(['auth', 'role:rt,bendahara,superadmin'])->group(function () {
    Route::get('/users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    
    Route::resource('users', UserController::class);

    Route::get('/iuran/master', [IuranController::class, 'indexMaster'])->name('iuran.master');
    Route::post('/iuran/master', [IuranController::class, 'storeMaster'])->name('iuran.master.store');
    Route::put('/iuran/master/{id}', [IuranController::class, 'updateMaster'])->name('iuran.master.update'); 
    Route::delete('/iuran/master/{id}', [IuranController::class, 'destroyMaster'])->name('iuran.master.destroy');
    Route::post('/iuran/generate', [IuranController::class, 'generateTagihan'])->name('iuran.generate');

    Route::get('/verifikasi/iuran', [VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::post('/verifikasi/iuran/{ids}/approve', [VerifikasiController::class, 'approve'])->name('verifikasi.approve');

    Route::get('/settings/payment', [PaymentSettingController::class, 'index'])->name('settings.payment');
    Route::post('/settings/payment', [PaymentSettingController::class, 'update'])->name('settings.payment.update');

    Route::resource('agendas', AgendaController::class);
    Route::patch('/agendas/{agenda}/update-status', [AgendaController::class, 'updateStatus'])->name('agendas.update-status');
    Route::patch('/agendas/{agenda}/reschedule', [AgendaController::class, 'reschedule'])->name('agendas.reschedule');

    Route::get('/expenditures', [ExpenditureController::class, 'index'])->name('expenditures.index');
    Route::post('/expenditures/foto/{id}', [ExpenditureController::class, 'uploadFoto']);
    Route::post('/expenditures/nota/{id}', [ExpenditureController::class, 'uploadNota']);

    Route::get('/laporan/kas', [KasController::class, 'index'])->name('laporan.kas');
    Route::get('/laporan/iuran-warga', [KasController::class, 'iuranWarga'])->name('laporan.iuran');
    Route::get('/tagihan/warga', [TagihanWargaController::class, 'index'])->name('tagihan.warga');
});

// 4. GRUP AKSES WARGA (Warga & Superadmin)
Route::middleware(['auth', 'role:warga,superadmin'])->group(function () {  
    Route::get('/my-iuran', [IuranController::class, 'wargaIndex'])->name('warga.iuran');
    Route::post('/my-iuran/bayar/{id}', [IuranController::class, 'bayar'])->name('warga.bayar'); 
    Route::post('/my-iuran/bayar-koperasi/{id}', [IuranController::class, 'bayarPakaiKoperasi'])->name('warga.bayar.koperasi');

    Route::get('/my-family', [UserController::class, 'profileWarga'])->name('warga.profile');
    Route::post('/my-family/update-profile', [UserController::class, 'updateProfileWarga'])->name('warga.profile.update');
    Route::post('/my-family/add', [UserController::class, 'storeFamily'])->name('warga.family.store');
    Route::post('/my-family/update-anggota/{id}', [UserController::class, 'updateFamily'])->name('warga.family.update');
    Route::delete('/my-family/delete/{id}', [UserController::class, 'destroyFamily'])->name('warga.family.destroy');

    Route::get('/transparansi/dana', [ExpenditureController::class, 'wargaIndex'])->name('warga.transparansi');
    
    Route::get('/infaq-saya', [InfaqController::class, 'index'])->name('warga.infaq');
    Route::post('/infaq-saya/store', [InfaqController::class, 'store'])->name('warga.infaq.store');

    Route::get('/koperasi/saya', [KoperasiWargaController::class, 'index'])->name('warga.koperasi');
    Route::post('/koperasi/saya/join', [KoperasiWargaController::class, 'join'])->name('warga.koperasi.join');
    Route::post('/koperasi/saya/setor', [KoperasiWargaController::class, 'store'])->name('warga.koperasi.store');
    Route::post('/koperasi/saya/tarik', [KoperasiWargaController::class, 'tarikDana'])->name('warga.koperasi.tarik');
    Route::post('/koperasi/saya/pinjam', [KoperasiWargaController::class, 'ajukanPinjaman'])->name('warga.koperasi.pinjam');
    Route::post('/koperasi/saya/cicilan/{id}', [KoperasiWargaController::class, 'bayarCicilan'])->name('warga.koperasi.bayar_cicilan');
});

// 5. GRUP AKSES PENGURUS MESJID (Mesjid & Superadmin)
Route::middleware(['auth', 'role:mesjid,superadmin'])->group(function () {
    Route::get('/mesjid/dashboard', [MesjidDashboardController::class, 'index'])->name('mesjid.dashboard');
    Route::get('/mesjid/payment', [MesjidPaymentController::class, 'index'])->name('mesjid.payment');
    Route::post('/mesjid/payment', [MesjidPaymentController::class, 'store'])->name('mesjid.payment.store');
    Route::get('/mesjid/pengeluaran', [MesjidExpenditureController::class, 'index'])->name('mesjid.pengeluaran');
    Route::post('/mesjid/pengeluaran', [MesjidExpenditureController::class, 'store'])->name('mesjid.pengeluaran.store');
    Route::delete('/mesjid/pengeluaran/{id}', [MesjidExpenditureController::class, 'destroy'])->name('mesjid.pengeluaran.destroy');});
    Route::get('/mesjid/laporan', [MesjidLaporanController::class, 'index'])->name('mesjid.laporan');

// 6. GRUP AKSES KOPERASI (Koperasi & Superadmin)
Route::middleware(['auth', 'role:koperasi,superadmin'])->group(function () {
    Route::get('/koperasi/dashboard', [KoperasiDashboardController::class, 'index'])->name('koperasi.admin.index');
    Route::get('/koperasi/setting-pembayaran', [KoperasiPaymentController::class, 'index'])->name('koperasi.admin.payment');
    Route::post('/koperasi/setting-pembayaran', [KoperasiPaymentController::class, 'store'])->name('koperasi.admin.payment.store');
    Route::get('/koperasi/transaksi', [KoperasiController::class, 'transaksi'])->name('koperasi.admin.transaksi');
    Route::get('/koperasi/anggota', [KoperasiController::class, 'anggota'])->name('koperasi.admin.anggota');
    Route::post('/koperasi/verify/{id}', [KoperasiController::class, 'verify'])->name('koperasi.admin.verify');
    Route::post('/koperasi/reject/{id}', [KoperasiController::class, 'reject'])->name('koperasi.admin.reject');
    Route::get('/koperasi/kasbon', [KoperasiController::class, 'kasbon'])->name('koperasi.admin.kasbon');
    Route::post('/koperasi/kasbon/{id}/approve', [KoperasiController::class, 'approveKasbon'])->name('koperasi.admin.approve_kasbon');
    Route::post('/koperasi/cicilan/{id}/verify', [KoperasiController::class, 'verifyCicilan'])->name('koperasi.admin.verify_cicilan');
    Route::post('/koperasi/cicilan/{id}/reject', [KoperasiController::class, 'rejectCicilan'])->name('koperasi.admin.reject_cicilan');
    Route::get('/koperasi/laporan-kas', [KoperasiLaporanController::class, 'index'])->name('koperasi.laporan.kas');
});

// 7. PROFILE BAWAAN LARAVEL BREEZE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';