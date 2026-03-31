<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', [BookingController::class, 'index'])->name('home');
Route::get('/packages', [BookingController::class, 'packages'])->name('packages');
Route::get('/gallery', [BookingController::class, 'gallery'])->name('gallery');
Route::get('/contact', [BookingController::class, 'contact'])->name('contact');
Route::get('/about', [BookingController::class, 'about'])->name('about');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success/{booking}', [BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/cancel/{booking}', [BookingController::class, 'cancel'])->name('booking.cancel');
Route::get('/booking/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/booking/available-slots', [BookingController::class, 'getAvailableSlots'])->name('booking.available-slots');

// Contact routes
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Serve gallery images through Laravel route
Route::get('/gallery/{filename}', function ($filename) {
    $path = storage_path('app/public/gallery/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('gallery.image');

// Admin routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::put('/admin/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('admin.bookings.update');
    Route::delete('/admin/bookings/{booking}', [AdminController::class, 'deleteBooking'])->name('admin.bookings.destroy');
    Route::post('/admin/check-in', [BookingController::class, 'checkIn'])->name('admin.check-in');
    Route::post('/admin/mark-paid', [BookingController::class, 'markPaidAtSite'])->name('admin.mark-paid');
    Route::post('/admin/complete', [BookingController::class, 'complete'])->name('admin.complete');
    Route::post('/admin/update-payment-status', [AdminController::class, 'updatePaymentStatus'])->name('admin.update-payment-status');
    Route::get('/admin/time-slots', [AdminController::class, 'timeSlots'])->name('admin.time-slots');
    Route::post('/admin/time-slots', [AdminController::class, 'storeTimeSlot'])->name('admin.time-slots.store');
    Route::put('/admin/time-slots/{timeSlot}', [AdminController::class, 'updateTimeSlot'])->name('admin.time-slots.update');
    Route::delete('/admin/time-slots/{timeSlot}', [AdminController::class, 'deleteTimeSlot'])->name('admin.time-slots.destroy');
    Route::get('/admin/testimonials', [AdminController::class, 'testimonials'])->name('admin.testimonials');
    Route::post('/admin/testimonials', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
    Route::put('/admin/testimonials/{testimonial}', [AdminController::class, 'updateTestimonial'])->name('admin.testimonials.update');
    Route::delete('/admin/testimonials/{testimonial}', [AdminController::class, 'deleteTestimonial'])->name('admin.testimonials.destroy');
    Route::get('/admin/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');
    Route::post('/admin/gallery', [AdminController::class, 'storeGalleryImage'])->name('admin.gallery.store');
    Route::put('/admin/gallery/{galleryImage}', [AdminController::class, 'updateGalleryImage'])->name('admin.gallery.update');
    Route::delete('/admin/gallery/{galleryImage}', [AdminController::class, 'deleteGalleryImage'])->name('admin.gallery.destroy');
    Route::get('/admin/contacts', [AdminController::class, 'contacts'])->name('admin.contacts');
    Route::post('/admin/contacts/mark-read', [AdminController::class, 'markContactRead'])->name('admin.contacts.mark-read');
    Route::post('/admin/contacts/delete', [AdminController::class, 'deleteContact'])->name('admin.contacts.delete');
    Route::get('/admin/packages', [AdminController::class, 'packages'])->name('admin.packages');
    Route::post('/admin/packages', [AdminController::class, 'storePackage'])->name('admin.packages.store');
    Route::put('/admin/packages/{package}', [AdminController::class, 'updatePackage'])->name('admin.packages.update');
    Route::delete('/admin/packages/{package}', [AdminController::class, 'deletePackage'])->name('admin.packages.destroy');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Admin/Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
