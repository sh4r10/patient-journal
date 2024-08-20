<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\TreatmentController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\UserController;



Route::get('/file-upload', [FileController::class, 'showUploadForm'])->name('file.upload');
Route::post('/file-upload', [FileController::class, 'store'])->name('file.store');

Route::get('/', function () {
    return view('welcome');
});

// TODO: fix this to avoid double redirect
Route::redirect('/', '/patients')->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('patients', PatientController::class);
    Route::get('/patients/{patient}/entries', [PatientController::class, 'show'])->name('patients.entries');
    Route::get('/patients/{id}/notes', [PatientController::class, 'showNotes'])->name('patients.notes');
    Route::post('/patients/{id}/notes', [PatientController::class, 'storeNote'])->name('patients.notes.store');

    Route::get('/patients/{patient}/treatments', [PatientController::class, 'showTreatments'])->name('patients.treatments');
    Route::get('/patients/{patient}/manage', [PatientController::class, 'edit'])->name('patients.manage');
  
    Route::resource('treatments', TreatmentController::class);
    Route::resource('entries', JournalEntryController::class, ['only' => ['store', 'edit', 'update', 'destroy']]);
    Route::get('/entries/create/{patient}', [JournalEntryController::class, 'create'])->name('entries.create');
    Route::get('/uploads/{filename}', [FileController::class, 'show'])->name('files.show');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');

    Route::get('/patients/{patient}/deleted-entries', [PatientController::class, 'showDeletedEntries'])
        ->name('patients.deleted.entries')
        ->middleware('auth');

    Route::get('/patients/deleted', [PatientController::class, 'showDeleted'])->name('patients.deleted')->middleware('auth');
    Route::post('/patients/restore/{id}', [PatientController::class, 'restore'])->name('patients.restore')->middleware('auth');

    Route::get('entries/deleted', [JournalEntryController::class, 'showDeleted'])->name('entries.deleted')->middleware('auth');
    Route::post('entries/restore/{id}', [JournalEntryController::class, 'restore'])->name('entries.restore')->middleware('auth');

    Route::middleware(['auth', 'adminMiddleware'])->group(function () {
        Route::get('/assistants', [UserController::class, 'index'])->name('assistants.index');
        Route::get('/assistants/create', [UserController::class, 'create'])->name('assistants.create');
        Route::post('/assistants', [UserController::class, 'store'])->name('assistants.store');
        Route::get('/assistants/{user}/edit', [UserController::class, 'edit'])->name('assistants.edit');
        Route::put('/assistants/{user}', [UserController::class, 'update'])->name('assistants.update');
        Route::get('/assistants/{user}', [UserController::class, 'show'])->name('assistants.show');
        Route::delete('/assistants/{user}', [UserController::class, 'destroy'])->name('assistants.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [UserController::class, 'logout'])->name('users.logout');
});


require __DIR__ . '/auth.php';
