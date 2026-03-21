<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', [BookingController::class, 'index'])->name('home');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success/{booking}', [BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/cancel/{booking}', [BookingController::class, 'cancel'])->name('booking.cancel');
Route::get('/booking/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/booking/available-slots', [BookingController::class, 'getAvailableSlots'])->name('booking.available-slots');

// Admin routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::post('/admin/check-in', [BookingController::class, 'checkIn'])->name('admin.check-in');
    Route::post('/admin/mark-paid', [BookingController::class, 'markPaidAtSite'])->name('admin.mark-paid');
    Route::post('/admin/complete', [BookingController::class, 'complete'])->name('admin.complete');
    Route::get('/admin/time-slots', [AdminController::class, 'timeSlots'])->name('admin.time-slots');
    Route::post('/admin/time-slots', [AdminController::class, 'storeTimeSlot'])->name('admin.time-slots.store');
    Route::get('/admin/testimonials', [AdminController::class, 'testimonials'])->name('admin.testimonials');
    Route::post('/admin/testimonials', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
    Route::get('/admin/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');
    Route::post('/admin/gallery', [AdminController::class, 'storeGalleryImage'])->name('admin.gallery.store');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
