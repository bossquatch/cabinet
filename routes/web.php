<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Pear\Crypt\GPG;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

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
