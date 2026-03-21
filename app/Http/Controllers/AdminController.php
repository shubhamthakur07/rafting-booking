<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\Testimonial;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $todayBookings = Booking::whereDate('booking_date', today())
            ->whereIn('booking_status', ['confirmed', 'checked_in'])
            ->count();

        $totalRevenue = Booking::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');

        $pendingPayments = Booking::where('payment_status', 'at_site')
            ->whereDate('booking_date', today())
            ->count();

        return inertia('Admin/Dashboard', [
            'todayBookings' => $todayBookings,
            'totalRevenue' => $totalRevenue,
            'pendingPayments' => $pendingPayments,
        ]);
    }

    public function bookings(Request $request)
    {
        $query = Booking::with('timeSlot')->orderBy('booking_date', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('booking_status', $request->status);
        }

        if ($request->has('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        $bookings = $query->paginate(20);

        return inertia('Admin/Bookings', [
            'bookings' => $bookings,
            'filters' => $request->only(['status', 'date']),
        ]);
    }

    public function timeSlots(Request $request)
    {
        $timeSlots = TimeSlot::orderBy('start_time')->get();

        return inertia('Admin/TimeSlots', [
            'timeSlots' => $timeSlots,
        ]);
    }

    public function storeTimeSlot(Request $request)
    {
        $validated = $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'max_people' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        TimeSlot::create($validated);

        return back()->with('success', 'Time slot created successfully!');
    }

    public function testimonials(Request $request)
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->get();

        return inertia('Admin/Testimonials', [
            'testimonials' => $testimonials,
        ]);
    }

    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_avatar' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'is_active' => 'boolean',
        ]);

        Testimonial::create($validated);

        return back()->with('success', 'Testimonial created successfully!');
    }

    public function gallery(Request $request)
    {
        $galleryImages = GalleryImage::orderBy('sort_order')->get();

        return inertia('Admin/Gallery', [
            'galleryImages' => $galleryImages,
        ]);
    }

    public function storeGalleryImage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image_path' => 'required|string',
            'category' => 'nullable|in:photos,videos',
            'video_url' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        GalleryImage::create($validated);

        return back()->with('success', 'Gallery item created successfully!');
    }
}
