<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogAbsenController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\JabatanController;
use Illuminate\Support\Facades\Route;

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

Route::post('/api-login-mobile',[UserController::class,'login_mobile']);
Route::post('/api-act-absensi',[UserController::class,'act_absensi']);
// Route::middleware('auth')->post('//api-act-absensi', [UserController::class, 'act_absensi']);



Route::middleware(['auth:web'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/print-cuti', [DashboardController::class, 'printCuti'])->name('dashboard.print-cuti');

    Route::prefix('/user-management')->group(function () {
        Route::name('user-management.')->group(function () {
            Route::controller(UserController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::delete('/destroy/{id}', 'destroy')->name('destroy');
            });
        });
    });

    Route::prefix('/log-absen')->group(function () {
        Route::name('log-absen.')->group(function () {
            Route::controller(LogAbsenController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/show/{id}', 'show')->name('detail');
                Route::get('/getLocation/{id}', 'getLocation')->name('getLocation');
                Route::post('/goAbsen', 'goAbsen')->name('goAbsen');
                Route::post('/createIzin', 'createIzin')->name('createIzin');
                Route::get('/printLaporan', 'printLaporan')->name('printLaporan');
                Route::get('/izinKaryawan/{id}', 'izinKaryawan')->name('izinKaryawan');
                Route::get('/detailIzinKaryawan/{id}', 'detailIzinKaryawan')->name('detailIzinKaryawan');
            });
        });
    });

    Route::prefix('/pengajuan-cuti')->group(function () {
        Route::name('pengajuan-cuti.')->group(function () {
            Route::controller(PengajuanCutiController::class)->group(function () {
                Route::get('/index-admin', 'indexAdmin')->name('index-admin');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/approval/{id}', 'approval')->name('approval');
                Route::delete('/destroy/{id}', 'destroy')->name('destroy');
                Route::get('/karyawan/{id}', 'index')->name('index');
            });
        });
    });

    Route::prefix('/departemen')->group(function () {
        Route::name('departemen.')->group(function () {
            Route::controller(DepartemenController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::delete('/destroy/{id}', 'destroy')->name('destroy');
            });
        });
    });
    
    Route::prefix('/jabatan')->group(function () {
        Route::name('jabatan.')->group(function () {
            Route::controller(JabatanController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::delete('/destroy/{id}', 'destroy')->name('destroy');
            });
        });
    });

});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
