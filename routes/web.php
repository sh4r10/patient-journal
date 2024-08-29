<?php

use App\Http\Controllers\DeletedDataController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\TreatmentController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\UserController;

// TODO: fix this to avoid double redirect
Route::redirect('/', '/patients')->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('patients', PatientController::class);
    Route::get('/patients/{patient}/entries', [PatientController::class, 'show'])->name('patients.entries');
    Route::get('/patients/{id}/notes', [PatientController::class, 'showNotes'])->name('patients.notes');
    Route::post('/patients/{id}/notes', [PatientController::class, 'storeNote'])->name('patients.notes.store');
    Route::delete('/notes/{note}', [PatientController::class, 'destroyNote'])->name('patients.notes.destroy');
    Route::get('/patients/{patient}/treatments', [PatientController::class, 'showTreatments'])->name('patients.treatments');
    Route::post('/patients/{patient}/assign-treatment', [PatientController::class, 'assignTreatment'])->name('patients.assignTreatment');
    Route::delete('/patients/{patient}/unassign-treatment/{treatment}', [PatientController::class, 'unassignTreatment'])->name('patients.unassignTreatment');
    Route::get('/patients/{patient}/manage', [PatientController::class, 'edit'])->name('patients.manage');
    Route::get('/patients/{patient}/deleted-entries', [PatientController::class, 'showDeletedEntries'])->name('patients.deleted.entries');
    Route::get('/patients/deleted', [PatientController::class, 'showDeleted'])->name('patients.deleted');
    Route::post('/patients/restore/{id}', [PatientController::class, 'restore'])->name('patients.restore');
    Route::resource('treatments', TreatmentController::class);

    // Journal Entry Routes
    Route::resource('entries', JournalEntryController::class, ['only' => ['store', 'edit', 'destroy']]);
    Route::get('/entries/create/{patient}', [JournalEntryController::class, 'create'])->name('entries.create');
    Route::get('/entries/deleted', [JournalEntryController::class, 'showDeleted'])->name('entries.deleted');
    Route::post('/entries/restore/{id}', [JournalEntryController::class, 'restore'])->name('entries.restore');
    Route::put('/entries/{entry}', [JournalEntryController::class, 'update'])->name('entries.update');
    Route::delete('/entries/{entry}', [JournalEntryController::class, 'destroy'])->name('entries.destroy');

    // File Routes
    Route::get('/uploads/{filename}', [FileController::class, 'show'])->name('files.show');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    Route::get('/file-upload', [FileController::class, 'showUploadForm'])->name('file.upload');
    Route::post('/file-upload', [FileController::class, 'store'])->name('file.store');

    Route::middleware(['auth', 'adminMiddleware'])->group(function () {
        Route::get('/assistants', [UserController::class, 'index'])->name('assistants.index');
        Route::get('/assistants/create', [UserController::class, 'create'])->name('assistants.create');
        Route::post('/assistants', [UserController::class, 'store'])->name('assistants.store');
        Route::get('/assistants/{user}/edit', [UserController::class, 'edit'])->name('assistants.edit');
        Route::put('/assitants/{user}', [UserController::class, 'update'])->name('assistants.update');
        Route::get('/assistants/{user}', [UserController::class, 'show'])->name('assistants.show');
        Route::delete('/assistants/{user}', [UserController::class, 'destroy'])->name('assistants.destroy');
        Route::delete('/data/deleted', [DeletedDataController::class, 'permanentlyDeleteDeletedItems'])->name('data.delete');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [UserController::class, 'logout'])->name('users.logout');
});

require __DIR__ . '/auth.php';
