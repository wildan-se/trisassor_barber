<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AvailableSlotController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ───────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// AJAX: cek slot tersedia
Route::get('/booking/slots', AvailableSlotController::class)->name('booking.slots');

// ─── Auth Routes (Breeze) ────────────────────────────────────────────────────
require __DIR__ . '/auth.php';

// ─── Customer Authenticated Routes ───────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Breeze default fallback redirect route:
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('booking.index');
    })->name('dashboard');

    // Customer Only Routes
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking',       [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking',        [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin', 'no-cache'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('/bookings',                          [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}',                [Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/confirm',      [Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/complete',     [Admin\BookingController::class, 'complete'])->name('bookings.complete');
    Route::patch('/bookings/{booking}/cancel',       [Admin\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Barbers
    Route::resource('barbers', Admin\BarberController::class);

    // Services
    Route::resource('services', Admin\ServiceController::class);

    // Schedules
    Route::get('/schedules',                         [Admin\BarberScheduleController::class, 'index'])->name('schedules.index');
    Route::put('/schedules/{barber}',                [Admin\BarberScheduleController::class, 'update'])->name('schedules.update');
});
