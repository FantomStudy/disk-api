<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileAccessController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/registration', [AuthController::class, 'registration']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/files/disk', [FileController::class, 'showMyFiles']);
    Route::get('/shared', [FileAccessController::class, 'showMyAccess']);

    Route::post('/files/{file:file_id}/accesses', [FileAccessController::class, 'addAccess'])->can('manage,file');
    Route::delete('/files/{file:file_id}/accesses', [FileAccessController::class, 'deleteAccess'])->can('manage,file');

    Route::post('/files', [FileController::class, 'upload']);
    Route::get('/files/{file:file_id}', [FileController::class, 'download'])->name('download')->can('view,file');
    Route::patch('/files/{file:file_id}', [FileController::class, 'edit'])->can('manage,file');
    Route::delete('/files/{file:file_id}', [FileController::class, 'delete'])->can('manage,file');
});
