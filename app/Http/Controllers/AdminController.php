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
            ->whereIn('booking_status', ['confirmed', 'pending'])
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

        if ($request->has('booking_status') && $request->booking_status !== 'all') {
            $query->where('booking_status', $request->booking_status);
        }

        if ($request->has('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        $bookings = $query->paginate(20);

        $timeSlots = TimeSlot::orderBy('start_time')->get();
        $packages = Package::orderBy('sort_order')->orderBy('km')->get();

        return inertia('Admin/Bookings', [
            'bookings' => $bookings,
            'timeSlots' => $timeSlots,
            'packages' => $packages,
            'filters' => $request->only(['booking_status', 'date']),
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
            'price' => 'numeric|min:0',
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

    public function updateBooking(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'package_id' => 'required|exists:packages,id',
            'number_of_people' => 'required|integer|min:1',
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        // Ensure booking_status is properly set
        $booking->booking_status = $validated['booking_status'];
        $booking->save();

        // Update other fields
        $booking->update(array_merge(
            $validated,
            ['booking_status' => $validated['booking_status']]
        ));

        return back()->with('success', 'Booking updated successfully!');
    }

    public function deleteBooking(Booking $booking)
    {
        $booking->delete();

        return back()->with('success', 'Booking deleted successfully!');
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
        $logoFilename = SiteSetting::getValue('logo_url', '');
        $faviconFilename = SiteSetting::getValue('favicon_url', '');

        $siteSettings = [
            'site_name' => SiteSetting::getValue('site_name', 'River Rafting Adventure'),
            'logo_url' => $logoFilename ? route('logo.image', ['filename' => $logoFilename]) : '',
            'favicon_url' => $faviconFilename ? route('favicon.image', ['filename' => $faviconFilename]) : '',
            'phone_number' => SiteSetting::getValue('phone_number', ''),
            'whatsapp_number' => SiteSetting::getValue('whatsapp_number', ''),
            'email' => SiteSetting::getValue('email', ''),
            'address' => SiteSetting::getValue('address', ''),
            'google_map_embed' => SiteSetting::getValue('google_map_embed', ''),
        ];

        return inertia('Admin/Settings', [
            'siteSettings' => $siteSettings,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'logo_file' => 'nullable|file|mimes:jpeg,png,gif,svg,webp|max:2048',
            'favicon_file' => 'nullable|file|mimes:jpeg,png,gif,ico,webp|max:512',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'google_map_embed' => 'nullable|string',
        ]);

        // Handle logo file upload
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            $file->storeAs('logo', $filename, 'public');
            SiteSetting::setValue('logo_url', $filename);
        }

        // Handle favicon file upload
        if ($request->hasFile('favicon_file')) {
            $file = $request->file('favicon_file');
            $filename = time() . '_favicon.' . $file->getClientOriginalExtension();
            $file->storeAs('favicon', $filename, 'public');
            SiteSetting::setValue('favicon_url', $filename);
        }

        SiteSetting::setValue('site_name', $validated['site_name'] ?? 'River Rafting Adventure');
        SiteSetting::setValue('phone_number', $validated['phone_number'] ?? '');
        SiteSetting::setValue('whatsapp_number', $validated['whatsapp_number'] ?? '');
        SiteSetting::setValue('email', $validated['email'] ?? '');
        SiteSetting::setValue('address', $validated['address'] ?? '');
        SiteSetting::setValue('google_map_embed', $validated['google_map_embed'] ?? '');

        return back()->with('success', 'Settings updated successfully!');
    }
}
