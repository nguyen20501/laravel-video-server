<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('upload', [VideoController::class, 'uploadVideo'])->name('video.upload');
Route::get('stream/{id}', [VideoController::class, 'streamVideo'])->name('video.stream');

