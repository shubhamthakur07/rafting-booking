<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\Testimonial;
use App\Models\GalleryImage;
use App\Models\Contact;
use App\Models\Package;
use App\Models\SiteSetting;
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

    public function updateTimeSlot(Request $request, TimeSlot $timeSlot)
    {
        $validated = $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'max_people' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $timeSlot->update($validated);

        return back()->with('success', 'Time slot updated successfully!');
    }

    public function deleteTimeSlot(TimeSlot $timeSlot)
    {
        $timeSlot->delete();

        return back()->with('success', 'Time slot deleted successfully!');
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

    public function updateTestimonial(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_avatar' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $testimonial->update($validated);

        return back()->with('success', 'Testimonial updated successfully!');
    }

    public function deleteTestimonial(Testimonial $testimonial)
    {
        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted successfully!');
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
            'image_file' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
            'image_path' => 'nullable|string',
            'category' => 'nullable|in:photos,videos',
            'video_url' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle file upload
        $imagePath = $validated['image_path'] ?? '';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('gallery', $filename, 'public');
            // Store just the filename for use with our custom route
            $imagePath = $filename;
        }

        GalleryImage::create([
            'title' => $validated['title'],
            'image_path' => $imagePath,
            'category' => $validated['category'] ?? 'photos',
            'video_url' => $validated['video_url'] ?? '',
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Gallery item created successfully!');
    }

    public function updateGalleryImage(Request $request, GalleryImage $galleryImage)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
            'image_path' => 'nullable|string',
            'category' => 'nullable|in:photos,videos',
            'video_url' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle file upload
        $imagePath = $galleryImage->image_path;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('gallery', $filename, 'public');
            $imagePath = $filename;
        }

        $galleryImage->update([
            'title' => $validated['title'],
            'image_path' => $imagePath,
            'category' => $validated['category'] ?? 'photos',
            'video_url' => $validated['video_url'] ?? '',
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Gallery item updated successfully!');
    }

    public function deleteGalleryImage(GalleryImage $galleryImage)
    {
        $galleryImage->delete();

        return back()->with('success', 'Gallery item deleted successfully!');
    }

    public function updatePaymentStatus(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_status' => 'required|in:pending,paid,at_site',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);
        $booking->update(['payment_status' => $validated['payment_status']]);

        return response()->json(['success' => true, 'message' => 'Payment status updated successfully']);
    }

    public function contacts(Request $request)
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();

        return inertia('Admin/Contacts', [
            'contacts' => $contacts,
        ]);
    }

    public function markContactRead(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
        ]);

        $contact = Contact::findOrFail($validated['contact_id']);
        $contact->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function deleteContact(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
        ]);

        Contact::findOrFail($validated['contact_id'])->delete();

        return response()->json(['success' => true, 'message' => 'Contact deleted successfully']);
    }

    public function packages()
    {
        $packages = Package::orderBy('sort_order')->orderBy('km')->get();

        return inertia('Admin/Packages', [
            'packages' => $packages,
        ]);
    }

    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'km' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        Package::create($validated);

        return back()->with('success', 'Package created successfully!');
    }

    public function updatePackage(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'km' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $package->update($validated);

        return back()->with('success', 'Package updated successfully!');
    }

    public function deletePackage(Package $package)
    {
        $package->delete();

        return back()->with('success', 'Package deleted successfully!');
    }

    public function settings()
    {
        $googleMapEmbed = SiteSetting::getValue('google_map_embed', '');

        return inertia('Admin/Settings', [
            'googleMapEmbed' => $googleMapEmbed,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'google_map_embed' => 'nullable|string',
        ]);

        SiteSetting::setValue('google_map_embed', $validated['google_map_embed'] ?? '');

        return back()->with('success', 'Settings updated successfully!');
    }
}
