<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiDiskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/disks', [ApiDiskController::class, 'index']);

Route::middleware('auth:sanctum')->get('/disks/{id}/files', [ApiDiskController::class, 'show']);
Route::middleware('auth:sanctum')->post('/disks/{id}/files', [ApiDiskController::class, 'upload']);

Route::middleware('auth:sanctum')->get('/disks/{id}/files/download', [ApiDiskController::class, 'download']);