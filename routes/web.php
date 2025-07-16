<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingDashboardController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\UserNotificationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard (User Side)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [BookingDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | BOOKINGS (User Side)
    |--------------------------------------------------------------------------
    */
    Route::post('/bookings', [BookingDashboardController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/edit', [BookingDashboardController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingDashboardController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingDashboardController::class, 'destroy'])->name('bookings.destroy');

    /*
    |--------------------------------------------------------------------------
    | PROFILE (User Side)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS (User Side)
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [UserNotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL (Bookings & Users)
    |--------------------------------------------------------------------------
    | ✅ Accessible only to admins
    */
    Route::middleware('can:viewAny,App\Models\Booking')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Manage Bookings
            Route::get('/bookings', [BookingAdminController::class, 'index'])->name('bookings.index');
            Route::delete('/bookings/{booking}', [BookingAdminController::class, 'destroy'])->name('bookings.destroy');

            // Manage Users
            Route::get('/users', [UserAdminController::class, 'index'])->name('users.index');
            Route::delete('/users/{user}', [UserAdminController::class, 'destroy'])->name('users.destroy');
        });
});

require __DIR__.'/auth.php';
