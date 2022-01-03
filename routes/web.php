<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Pear\Crypt\GPG;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverFieldController;
use App\Http\Controllers\DiskController;
use App\Http\Controllers\DiskDriverFieldController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Auth\OracleIDCSSocialiteController;

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

Route::get('/', function () {
    return redirect()->route('dashboard');
    /*
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
    */
});

// Oracle IDCS Auth
Route::get('/oracle-idcs/login', [OracleIDCSSocialiteController::class, 'redirectToOracleIDCS'])->name('oracle-idcs.login');
Route::get('/oracle-idcs/callback', [OracleIDCSSocialiteController::class, 'handleCallback']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('dashboard');

/**
 * Upload routes
 */

Route::get('/upload', [UploadController::class, 'index'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('upload.form');

Route::post('/upload', [UploadController::class, 'upload'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('upload.file');

/**
 * Driver routes
 */

Route::get('/driver', [DriverController::class, 'index'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('driver.index');

Route::get('/driver/create', [DriverController::class, 'create'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('driver.create');

Route::post('/driver', [DriverController::class, 'store'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('driver.store');

Route::get('/driver/{driver}', [DriverController::class, 'show'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('driver.show');

Route::put('/driver/{driver}', [DriverController::class, 'update'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('driver.update');

Route::post('/driver/{driver}/fields', [DriverFieldController::class, 'store'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('driver-fields.store');

Route::put('/driver/{driver}/fields/{field}', [DriverFieldController::class, 'update'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('driver-fields.update');

Route::delete('/driver/{driver}/fields/{field}', [DriverFieldController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('driver-fields.destroy');

/**
 * Disk Routes
 */

Route::get('/disk', [DiskController::class, 'index'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('disk.index');

Route::get('/disk/create', [DiskController::class, 'create'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('disk.create');

Route::post('/disk', [DiskController::class, 'store'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('disk.store');

Route::get('/disk/{disk}', [DiskController::class, 'show'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('disk.show');

Route::put('/disk/{disk}', [DiskController::class, 'update'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('disk.update');

Route::post('/disk/{disk}/fields', [DiskDriverFieldController::class, 'store'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('disk-fields.store');

Route::get('/disk/{disk}/files', [DiskController::class, 'files'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('disk.files');

Route::get('/disk/{disk}/files/download', [DiskController::class, 'download'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('disk.file-download');

Route::get('/disk/{disk}/files/delete', [DiskController::class, 'deleteFile'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('disk.file-delete');

/**
 * File Routes
 */



/*
Route::get('/test', function () {
    phpinfo();
});

Route::get('/test/encrypt', function () {
    $gpg = new gnupg();

    $fileCsv = file_get_contents(storage_path('app') . '/testfile.csv');
    $pubKey = file_get_contents('../0xEC5D7470-pub.asc');

    $info = $gpg->import($pubKey);
    $encKey = $gpg->addencryptkey($info['fingerprint']);
    $encryptedData = $gpg->encrypt($fileCsv);

    Storage::disk('local')->put('test-encrypted.pgp', $encryptedData);

    return true;
});

Route::get('/test/decrypt', function () {
    $gpg = new gnupg();

    $filePgp = file_get_contents(storage_path('app') . '/test-encrypted.pgp');
    $priKey = file_get_contents('../0xEC5D7470-sec.asc');

    $info = $gpg->import($priKey);
    $encKey = $gpg->adddecryptkey($info['fingerprint'],'PL72Z2xSbiTxE9B');
    $decryptedData = $gpg->decrypt($filePgp);

    Storage::disk('local')->put('test-decrypted.csv', $decryptedData);

    return true;
});
*/
