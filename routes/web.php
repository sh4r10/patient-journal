<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'login'])->name('login');

// Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
// Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
// Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
// Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show');
// Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
// Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
// Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');

// the line below does the same thing as the 7 routes above
Route::resource('patients', PatientController::class);

Route::resource('entry', JournalEntry::class);
