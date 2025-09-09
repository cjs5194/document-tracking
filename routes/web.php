<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\DocumentController;

// Redirect root to login
Route::get('/', [AuthenticatedSessionController::class, 'create']);

// Authenticated user profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -----------------------------
// Regular Users (records & oed)
// -----------------------------
Route::middleware(['auth', 'role:users|oed|records'])->group(function () {
    Route::resource('documents', DocumentController::class)
        ->names('documents')
        ->except(['edit', 'update', 'destroy']);

    Route::patch('/documents/{document}', [DocumentController::class, 'update'])
        ->name('documents.update');

    // OED routes
    Route::post('/documents/oed-receive-all', [DocumentController::class, 'markAllOedReceived'])
        ->name('documents.oed.receive.all');
    Route::post('/documents/{document}/oed-receive', [DocumentController::class, 'markAsReceived'])
        ->name('documents.oed.receive.single');
    Route::post('/documents/{document}/oed-status', [DocumentController::class, 'updateOedStatus'])
        ->name('documents.oed.status');
    Route::post('/documents/{document}/remarks', [DocumentController::class, 'updateOedRemarks'])
        ->name('documents.oed.remarks');
    Route::post('/documents/{id}/forward-to-records', [DocumentController::class, 'markForwardedToRecords'])
        ->name('documents.forwardToRecords');

    // Records routes
    Route::post('/documents/{document}/records-receive', [DocumentController::class, 'markReceivedByRecords'])
        ->name('documents.records.receive');
    Route::post('/documents/{document}/records-return', [DocumentController::class, 'returnToOed'])
        ->name('documents.records.return');
    Route::post('/documents/{document}/records-remarks', [DocumentController::class, 'updateRecordsRemarks'])
        ->name('documents.records.remarks');
    Route::patch('/documents/{document}/completed', [DocumentController::class, 'markCompleted'])
        ->name('documents.markCompleted');
    Route::post('/documents/{id}/forwarded-to-oed', [DocumentController::class, 'markForwardedToOED'])
        ->name('documents.forwarded_to_oed');
    Route::post('/documents/{document}/send', [DocumentController::class, 'sendToUsers'])->name('documents.send');

});

// ✅ Separate group for delete (admin + records only)
Route::middleware(['auth', 'role:admin|records'])->group(function () {
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])
        ->name('documents.destroy');

    // ✅ Add export route here so only admin & records can access
    Route::get('/documents/export/csv', [DocumentController::class, 'export'])
        ->name('documents.export.csv');
});

// -----------------------------
// Admin-only routes
// -----------------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('documents', DocumentController::class);
    });

require __DIR__ . '/auth.php';
