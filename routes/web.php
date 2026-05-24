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
use App\Http\Controllers\RtDashboardController;
use App\Http\Controllers\StatistikWargaController;
use App\Http\Controllers\BendaharaDashboardController;
use App\Http\Controllers\WargaDashboardController;
use App\Http\Controllers\SuperadminDashboardController;
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
    
    if ($role === 'rt') {
        return app(RtDashboardController::class)->index();
    }

    if ($role === 'bendahara') {
        return app(BendaharaDashboardController::class)->index();
    }

    if ($role === 'warga') {
        return app(WargaDashboardController::class)->index();
    }
            
    if ($role === 'superadmin') {
        return app(SuperadminDashboardController::class)->index();
    }
    if (view()->exists($role . '.dashboard')) {
        return view($role . '.dashboard');
    }
 
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. GRUP AKSES ADMIN (RT, BENDAHARA, & SUPERADMIN)
Route::middleware(['auth', 'role:rt,bendahara,superadmin'])->group(function () {
    Route::get('/users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
    Route::get('/users/{id}/print', [UserController::class, 'printDetail'])->name('warga.print');
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
    Route::get('/statistik-warga', [StatistikWargaController::class, 'index'])->name('statistik.warga');
});

// 4. GRUP AKSES WARGA (Bisa diakses oleh semua role)
Route::middleware(['auth', 'role:warga,superadmin,rt,bendahara'])->group(function () {  
    Route::get('/my-iuran', [IuranController::class, 'wargaIndex'])->name('warga.iuran');
    Route::post('/my-iuran/bayar/{id}', [IuranController::class, 'bayar'])->name('warga.bayar'); 

    Route::get('/my-family', [UserController::class, 'profileWarga'])->name('warga.profile');
    Route::post('/my-family/update-profile', [UserController::class, 'updateProfileWarga'])->name('warga.profile.update');
    Route::post('/my-family/add', [UserController::class, 'storeFamily'])->name('warga.family.store');
    Route::post('/my-family/update-anggota/{id}', [UserController::class, 'updateFamily'])->name('warga.family.update');
    Route::delete('/my-family/delete/{id}', [UserController::class, 'destroyFamily'])->name('warga.family.destroy');

    Route::get('/kegiatan-rt', [AgendaController::class, 'wargaIndex'])->name('warga.agendas');

    Route::get('/transparansi/dana', [ExpenditureController::class, 'wargaIndex'])->name('warga.transparansi');
});

// 7. PROFILE BAWAAN LARAVEL BREEZE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';