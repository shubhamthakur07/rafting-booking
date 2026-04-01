<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\Testimonial;
use App\Models\GalleryImage;
use App\Models\Package;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Razorpay\Api\Api;
class BookingController extends Controller
{
    public function index()
    {

        $timeSlots = TimeSlot::where('is_active', true)->get();
        $packages = Package::getActivePackages();
        $testimonials = Testimonial::active()->take(6)->get();
        $galleryImages = GalleryImage::photos()->active()->take(12)->get();
        $videos = GalleryImage::videos()->active()->take(4)->get();
        $googleMapEmbed = SiteSetting::getValue('google_map_embed', '');

        // Get site settings for FloatingContact component
        $siteSettings = [
            'phoneNumber' => SiteSetting::getValue('phone_number', ''),
            'whatsappNumber' => SiteSetting::getValue('whatsapp_number', ''),
            'email' => SiteSetting::getValue('email', ''),
            'address' => SiteSetting::getValue('address', ''),
            'logoUrl' => SiteSetting::getValue('logo_url', '/storage/LOGO/SiteLogo.png'),
        ];

        return inertia('Booking/Index', [
            'timeSlots' => $timeSlots,
            'packages' => $packages,
            'testimonials' => $testimonials,
            'galleryImages' => $galleryImages,
            'videos' => $videos,
            'googleMapEmbed' => $googleMapEmbed,
            'siteSettings' => $siteSettings,
        ]);
    }

    public function getAvailableSlots(Request $request)
    {
        $date = $request->input('date');

        $timeSlots = TimeSlot::where('is_active', true)->get()->map(function ($slot) use ($date) {
            $available = $slot->getAvailableSlotsForDate($date);
            return [
                'id' => $slot->id,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'price' => $slot->price,
                'max_people' => $slot->max_people,
                'available' => $available,
            ];
        });

        return response()->json($timeSlots);
    }

//   public function store(Request $request)
// {
//     $validated = $request->validate([
//         'time_slot_id' => 'required|exists:time_slots,id',
//         'booking_date' => 'required|date|after_or_equal:today',
//         'customer_name' => 'required|string|max:255',
//         'customer_email' => 'required|email|max:255',
//         'customer_phone' => 'required|string|max:20',
//         'number_of_people' => 'required|integer|min:1|max:20',
//         'payment_method' => 'required|in:online,at_site',
//         'notes' => 'nullable|string',
//     ]);

//     $timeSlot = TimeSlot::findOrFail($validated['time_slot_id']);

//     // Check availability
//     if (!$timeSlot->isAvailableForDate($validated['booking_date'], $validated['number_of_people'])) {
//         // For AJAX requests, return a 422 JSON error
//         if ($request->wantsJson()) {
//             return response()->json(['message' => 'Not enough slots available.'], 422);
//         }
//         return back()->withErrors(['error' => 'Not enough slots available for this date.']);
//     }

//     $totalPrice = $timeSlot->price * $validated['number_of_people'];

//     $booking = Booking::create([
//         'time_slot_id' => $validated['time_slot_id'],
//         'booking_date' => $validated['booking_date'],
//         'customer_name' => $validated['customer_name'],
//         'customer_email' => $validated['customer_email'],
//         'customer_phone' => $validated['customer_phone'],
//         'number_of_people' => $validated['number_of_people'],
//         'total_price' => $totalPrice,
//         'payment_status' => $validated['payment_method'] === 'online' ? 'pending' : 'at_site',
//         'booking_status' => 'confirmed',
//         'notes' => $validated['notes'] ?? null,
//     ]);

//     // QR Code Generation logic...
//     $qrData = json_encode([
//         'ref' => $booking->booking_reference,
//         'date' => $booking->booking_date->format('Y-m-d'),
//         'time' => $timeSlot->start_time,
//         'people' => $booking->number_of_people,
//         'status' => $booking->payment_status,
//     ]);
//     $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($qrData));
//     $booking->update(['qr_code' => $qrCode]);

//     if ($validated['payment_method'] === 'online') {
//         Stripe::setApiKey(config('services.stripe.secret'));

//         $session = Session::create([
//             'payment_method_types' => ['card'],
//             'line_items' => [[
//                 'price_data' => [
//                     'currency' => 'inr',
//                     'product_data' => [
//                         'name' => 'River Rafting - ' . $booking->booking_reference,
//                     ],
//                     'unit_amount' => (int)($totalPrice * 100),
//                 ],
//                 'quantity' => 1,
//             ]],
//             'mode' => 'payment',
//             'success_url' => url('/booking/success/' . $booking->id),
//             'cancel_url' => url('/booking/cancel/' . $booking->id),
//         ]);

//         // CHANGE THIS: Return JSON instead of a direct redirect
//         if ($request->wantsJson()) {
//             return response()->json(['url' => $session->url]);
//         }

//         return redirect($session->url);
//     }

//     // For pay at site
//     if ($request->wantsJson()) {
//         return response()->json(['url' => route('booking.confirmation', $booking->id)]);
//     }

//     return redirect()->route('booking.confirmation', $booking->id);
// }

   public function store(Request $request)
{
    // ... (Keep your validation and Booking::create logic the same) ...

      $validated = $request->validate([
        'time_slot_id' => 'required|exists:time_slots,id',
        'package_id' => 'required|exists:packages,id',
        'booking_date' => 'required|date|after_or_equal:today',
        'customer_name' => 'required|string|max:255',
        'customer_email' => 'required|email|max:255',
        'customer_phone' => 'required|string|max:20',
        'number_of_people' => 'required|integer|min:1|max:20',
        'payment_method' => 'required|in:online,at_site',
        'notes' => 'nullable|string',
    ]);

    $timeSlot = TimeSlot::findOrFail($validated['time_slot_id']);
    $package = Package::findOrFail($validated['package_id']);

    // Check availability
    if (!$timeSlot->isAvailableForDate($validated['booking_date'], $validated['number_of_people'])) {
        // For AJAX requests, return a 422 JSON error
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Not enough slots available.'], 422);
        }
        return back()->withErrors(['error' => 'Not enough slots available for this date.']);
    }

    $totalPrice = $package->price * $validated['number_of_people'];

    $booking = Booking::create([
        'time_slot_id' => $validated['time_slot_id'],
        'package_id' => $validated['package_id'],
        'booking_date' => $validated['booking_date'],
        'customer_name' => $validated['customer_name'],
        'customer_email' => $validated['customer_email'],
        'customer_phone' => $validated['customer_phone'],
        'number_of_people' => $validated['number_of_people'],
        'total_price' => $totalPrice,
        'payment_status' => $validated['payment_method'] === 'online' ? 'pending' : 'at_site',
        'booking_status' => 'confirmed',
        'notes' => $validated['notes'] ?? null,
    ]);

    // QR Code Generation logic...
    $qrData = json_encode([
        'ref' => $booking->booking_reference,
        'date' => $booking->booking_date->format('Y-m-d'),
        'time' => $timeSlot->start_time,
        'people' => $booking->number_of_people,
        'status' => $booking->payment_status,
    ]);
    $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($qrData));
    $booking->update(['qr_code' => $qrCode]);

    if ($validated['payment_method'] === 'online') {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // Create Razorpay Order
        $orderData = [
            'receipt'         => (string) $booking->id,
            'amount'          => $totalPrice * 100, // Amount in paise (₹1 = 100 paise)
            'currency'        => 'INR',
            'payment_capture' => 1 // Auto-capture payment
        ];

        $razorpayOrder = $api->order->create($orderData);

        // Save the order_id to your booking record
        $booking->update(['razorpay_order_id' => $razorpayOrder['id']]);

        return response()->json([
            'payment_type' => 'razorpay',
            'amount' => $orderData['amount'],
            'order_id' => $razorpayOrder['id'],
            'key' => env('RAZORPAY_KEY'),
            'name' => $booking->customer_name,
            'email' => $booking->customer_email,
            'phone' => $booking->customer_phone,
            'booking_id' => $booking->id
        ]);
    }

    // For 'at_site' payment, return booking_id for redirect
    return response()->json([
        'payment_type' => 'at_site',
        'booking_id' => $booking->id
    ]);
}

    public function success(Booking $booking)
    {
        $booking->markAsPaid();

        return redirect()->route('booking.confirmation', $booking->id);
    }

    public function cancel(Booking $booking)
    {
        $booking->cancel();

        return redirect('/')->with('error', 'Payment was cancelled. Your booking has been cancelled.');
    }

    public function confirmation(Booking $booking)
    {
        $booking->load('timeSlot');

        // Get site settings for FloatingContact component
        $siteSettings = [
            'phoneNumber' => SiteSetting::getValue('phone_number', ''),
            'whatsappNumber' => SiteSetting::getValue('whatsapp_number', ''),
            'email' => SiteSetting::getValue('email', ''),
            'address' => SiteSetting::getValue('address', ''),
            'logoUrl' => SiteSetting::getValue('logo_url', '/storage/LOGO/SiteLogo.png'),
        ];

        return inertia('Booking/Confirmation', [
            'booking' => $booking,
            'siteSettings' => $siteSettings,
        ]);
    }

    public function checkIn(Request $request)
    {
        $reference = $request->input('reference');

        $booking = Booking::where('booking_reference', $reference)->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        if ($booking->booking_status === 'cancelled') {
            return response()->json(['error' => 'This booking has been cancelled'], 400);
        }

        if ($booking->booking_status === 'completed') {
            return response()->json(['error' => 'This booking has already been completed'], 400);
        }

        // If paying at site, mark as paid first
        if ($booking->payment_status === 'at_site') {
            $booking->markAsPaidAtSite();
        }

        $booking->checkIn();

        return response()->json([
            'success' => true,
            'booking' => $booking,
            'message' => 'Customer checked in successfully!',
        ]);
    }

    public function markPaidAtSite(Request $request)
    {
        $reference = $request->input('reference');

        $booking = Booking::where('booking_reference', $reference)->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $booking->markAsPaidAtSite();

        return response()->json([
            'success' => true,
            'booking' => $booking,
            'message' => 'Payment marked as paid at site!',
        ]);
    }

    public function complete(Request $request)
    {
        $reference = $request->input('reference');

        $booking = Booking::where('booking_reference', $reference)->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $booking->complete();

        return response()->json([
            'success' => true,
            'booking' => $booking,
            'message' => 'Booking marked as completed!',
        ]);
    }

    public function packages()
    {
        $packages = Package::getActivePackages();
        $googleMapEmbed = SiteSetting::getValue('google_map_embed', '');

        // Get site settings for FloatingContact component
        $siteSettings = [
            'phoneNumber' => SiteSetting::getValue('phone_number', ''),
            'whatsappNumber' => SiteSetting::getValue('whatsapp_number', ''),
            'email' => SiteSetting::getValue('email', ''),
            'address' => SiteSetting::getValue('address', ''),
            'logoUrl' => SiteSetting::getValue('logo_url', '/storage/LOGO/SiteLogo.png'),
        ];

        return inertia('Packages', [
            'packages' => $packages,
            'googleMapEmbed' => $googleMapEmbed,
            'siteSettings' => $siteSettings,
        ]);
    }

    public function gallery()
    {
        $galleryImages = GalleryImage::photos()->active()->get();
        $videos = GalleryImage::videos()->active()->get();
        $googleMapEmbed = SiteSetting::getValue('google_map_embed', '');

        // Get site settings for FloatingContact component
        $siteSettings = [
            'phoneNumber' => SiteSetting::getValue('phone_number', ''),
            'whatsappNumber' => SiteSetting::getValue('whatsapp_number', ''),
            'email' => SiteSetting::getValue('email', ''),
            'address' => SiteSetting::getValue('address', ''),
            'logoUrl' => SiteSetting::getValue('logo_url', '/storage/LOGO/SiteLogo.png'),
        ];

        return inertia('Gallery', [
            'galleryImages' => $galleryImages,
            'videos' => $videos,
            'googleMapEmbed' => $googleMapEmbed,
            'siteSettings' => $siteSettings,
        ]);
    }

    public function contact()
    {
        $googleMapEmbed = SiteSetting::getValue('google_map_embed', '');

        // Get site settings for FloatingContact component
        $siteSettings = [
            'phoneNumber' => SiteSetting::getValue('phone_number', ''),
            'whatsappNumber' => SiteSetting::getValue('whatsapp_number', ''),
            'email' => SiteSetting::getValue('email', ''),
            'address' => SiteSetting::getValue('address', ''),
            'logoUrl' => SiteSetting::getValue('logo_url', '/storage/LOGO/SiteLogo.png'),
        ];

        return inertia('Contact', [
            'googleMapEmbed' => $googleMapEmbed,
            'siteSettings' => $siteSettings,
        ]);
    }

    public function about()
    {
        $googleMapEmbed = SiteSetting::getValue('google_map_embed', '');

        // Get site settings for FloatingContact component
        $siteSettings = [
            'phoneNumber' => SiteSetting::getValue('phone_number', ''),
            'whatsappNumber' => SiteSetting::getValue('whatsapp_number', ''),
            'email' => SiteSetting::getValue('email', ''),
            'address' => SiteSetting::getValue('address', ''),
            'logoUrl' => SiteSetting::getValue('logo_url', '/storage/LOGO/SiteLogo.png'),
        ];

        return inertia('About', [
            'googleMapEmbed' => $googleMapEmbed,
            'siteSettings' => $siteSettings,
        ]);
    }
}
