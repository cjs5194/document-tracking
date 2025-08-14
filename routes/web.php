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

// Document routes for regular users (non-admin)
Route::middleware(['auth', 'role:user|oed|records|admin'])->group(function () {
    Route::resource('documents', DocumentController::class)
        ->names('documents')
        ->except(['edit', 'update', 'destroy']);
});

// ✅ OED-specific routes
Route::middleware(['auth', 'role:oed'])->group(function () {
    Route::post('/documents/oed-receive-all', [DocumentController::class, 'markAllOedReceived'])
        ->name('documents.oed.receive.all');

    // 🔽 Single document "Mark as Received" route
    Route::post('/documents/{document}/oed-receive', [DocumentController::class, 'markAsReceived'])
        ->name('documents.oed.receive.single');

    Route::post('/documents/{document}/oed-status', [DocumentController::class, 'updateOedStatus'])
    ->name('documents.oed.status');

    Route::post('/documents/{document}/remarks', [DocumentController::class, 'updateOedRemarks'])
    ->name('documents.oed.remarks')
    ->middleware('role:oed');
});

// ✅ Records-specific routes
Route::middleware(['auth', 'role:records'])->group(function () {
    Route::post('/documents/{document}/records-receive', [DocumentController::class, 'markReceivedByRecords'])
        ->name('documents.records.receive');

    Route::post('/documents/{document}/records-return', [DocumentController::class, 'returnToOed'])
        ->name('documents.records.return');

    Route::post('/documents/{document}/records-remarks', [DocumentController::class, 'updateRecordsRemarks'])
    ->name('documents.records.remarks')
    ->middleware('role:records');

    Route::patch('/documents/{document}/completed', [DocumentController::class, 'markCompleted'])
        ->name('documents.markCompleted');
});

// Admin-only delete user route
Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

// Permission-based route group (custom logic if needed)
Route::middleware(['permission:publish articles'])->group(function () {
    // Restricted routes here
});

// Admin panel routes with prefix and admin.* naming
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
