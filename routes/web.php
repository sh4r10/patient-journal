<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\JournalEntryController;



Route::get('/file-upload', [FileController::class, 'showUploadForm'])->name('file.upload');
Route::post('/file-upload', [FileController::class, 'store'])->name('file.store');



Route::get('/', function () {
    return view('welcome');
});

// TODO: fix this to avoid double redirect
Route::redirect('/', '/patients')->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('patients', PatientController::class);
    Route::resource('entries', JournalEntryController::class, ['only' => ['store', 'edit', 'update', 'destroy']]);
    Route::get('/entries/create/{patient}', [JournalEntryController::class, 'create'])->name('entries.create');
    Route::get('/uploads/{filename}', [FileController::class, 'show'])->name('files.show');
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});








/*
// File upload routes
Route::get('/file-upload', [FileController::class, 'showUploadForm'])->name('file.upload');
Route::post('/file-upload', [FileController::class, 'store'])->name('file.store');

// Entry modification routes
Route::middleware('auth')->group(function () {
    Route::get('/entries/edit/{journalEntry}', [JournalEntryController::class, 'edit'])->name('entries.edit');
    Route::put('/entries/update/{journalEntry}', [JournalEntryController::class, 'update'])->name('entries.update');
    Route::delete('/entries/delete/{journalEntry}', [JournalEntryController::class, 'destroy'])->name('entries.delete');
    Route::get('/entries/create/{patient}', [JournalEntryController::class, 'create'])->name('entries.create');
    Route::resource('entries', JournalEntryController::class, ['only' => ['store', 'show', 'index']]);
});

// Patient routes
Route::middleware('auth')->group(function () {
    Route::resource('patients', PatientController::class);
    Route::get('/uploads/{filename}', [FileController::class, 'show'])->name('files.show');
});

Route::middleware('auth')->delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Authentication and home routes
Route::get('/', function () {
    return view('welcome');
})->middleware('guest'); // Use guest middleware to only show this page to non-authenticated users

// Redirect home route to patients route for authenticated users
Route::get('/home', function () {
    return redirect('/patients');
})->middleware('auth')->name('dashboard');
*/
require __DIR__ . '/auth.php';



