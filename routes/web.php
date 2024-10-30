<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MobileController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// mobile routes
Route::name('mobile.')->group(function () {
    // login mobile
    Route::controller(AuthController::class)->name('auth.')->group(function () {
        Route::get('/', 'login')->name('login');
        Route::post('/', 'loginAction')->name('loginAction');
        Route::get('/logout', 'logout')->name('logout');
    });
    
    // view mobile
    Route::controller(ViewController::class)->prefix('views')->name('views.')->middleware(['isLogin'])->group(function () {
        Route::get('home', 'home')->name('home');
        Route::get('presensi', 'presensi')->name('presensi');
        Route::get('profile', 'profile')->name('profile');
        Route::get('absen-masuk', 'absenmasuk')->name('absenmasuk');
        Route::get('absen-pulang', 'absenpulang')->name('absenpulang');
        Route::get('surat-izin', 'suratizin')->name('suratizin');
        Route::get('pengajuan-cuti', 'pengajuancuti')->name('pengajuancuti');
        Route::get('cek-status', 'cekstatus')->name('cekstatus');
    });
    
    // api mobile
    Route::controller(MobileController::class)->prefix('api/v1')->name('api.')->middleware(['isLogin'])->group(function(){
        // setting
        Route::get('/setting', 'setting')->name('setting');
    
        // absen masuk
        Route::get('/cekabsenmasuk', 'cekabsenmasuk')->name('cekabsenmasuk');
        Route::post('/absenmasuk', 'absenmasuk')->name('absenmasuk');
    
        // absen pulang
        Route::get('/cekabsenpulang', 'cekabsenpulang')->name('cekabsenpulang');
        Route::post('/absenpulang', 'absenpulang')->name('absenpulang');
    
        //surat izin
        Route::post('/suratizin', 'suratizin')->name('suratizin');
        Route::post('/suratcuti', 'suratcuti')->name('suratcuti');
        
        // list pengajuan izin / cuti
        Route::post('/getpengajuan', 'getpengajuan')->name('getpengajuan');

        // profile
        Route::get('/getprofile', 'getprofile')->name('getprofile');
        Route::post('/updateprofile', 'updateprofile')->name('updateprofile'); 
    });
});


// admin controller
Route::name('admin.')->prefix('admin')->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/', 'login')->name('login');
        Route::post('/', 'loginAction')->name('loginAction');
        Route::get('/logout', 'logout')->name('logout');
    });

    Route::middleware('isLoginAdmin')->group(function(){
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::post('/user/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');

        Route::resource('user', App\Http\Controllers\UserController::class)->except('show', 'destroy');

        Route::resource('setting', App\Http\Controllers\SettingController::class)->only('index', 'update');

        Route::post('/suratcuti/{suratcuti}', [App\Http\Controllers\SuratcutiController::class, 'update'])->name('suratcuti.update');
        Route::post('/suratizin/{suratizin}', [App\Http\Controllers\SuratizinController::class, 'update'])->name('suratizin.update');

        Route::resource('suratcuti', App\Http\Controllers\SuratcutiController::class)->only('index');
        Route::resource('suratizin', App\Http\Controllers\SuratizinController::class)->only('index');

        Route::resource('absen', App\Http\Controllers\AbsenController::class)->only('index');
    });
});