<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/file-upload', [FileController::class, 'showUploadForm'])->name('file.upload');
Route::post('/files', [FileController::class, 'store'])->name('file.store');

Route::get('/', function () {
    return view('welcome');
});

// TODO: fix this to avoid double redirect
Route::redirect('/', '/patients')->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('patients', PatientController::class);
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__ . '/auth.php';
