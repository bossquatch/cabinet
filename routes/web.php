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
use App\Http\Controllers\DiskLogController;
use App\Http\Controllers\DiskDriverFieldController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\KeyAccessRequestController;
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
 * Key routes
 */

Route::get('/key', [KeyController::class, 'index'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.index');

Route::get('/key/create', [KeyController::class, 'create'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.create');

Route::post('/key', [KeyController::class, 'store'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.store');

Route::get('/key/{key}', [KeyController::class, 'show'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.show');

Route::put('/key/{key}', [KeyController::class, 'update'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.update');

Route::post('/key/share', [KeyController::class, 'userShare'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.userShare');

Route::post('/key/createCategory', [KeyController::class, 'createCategory'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.createCategory');

    Route::post('/key/updateCategory', [KeyController::class, 'updateCategory'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.updateCategory');

Route::post('/key/{team}', [KeyController::class, 'teamShare'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.teamShare');

Route::delete('/key/category/{key}', [KeyController::class, 'removeCategory'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.removeCategory');

Route::delete('/key/{key}/{user}', [KeyController::class, 'revoke'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.revoke');

Route::delete('/key/{key}', [KeyController::class, 'delete'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('key.delete');

/**
 * Request routes
 */

Route::get('/request', [KeyAccessRequestController::class, 'index'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('request.index');

Route::get('/request/create', [KeyAccessRequestController::class, 'create'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('request.create');

Route::post('/request', [KeyAccessRequestController::class, 'store'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('request.store');

Route::delete('/request/{req}', [KeyAccessRequestController::class, 'delete'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('request.delete');

Route::put('/request/{req}', [KeyAccessRequestController::class, 'approveRequest'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('request.approveRequest');

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
 * Log Routes
 */

Route::get('/log', [DiskLogController::class, 'index'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('log.index');

Route::get('/log/disk/{disk}', [DiskLogController::class, 'disk'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('log.disk');

Route::get('/log/user/{user}', [DiskLogController::class, 'user'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('log.user');

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
