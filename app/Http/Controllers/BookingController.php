<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\Testimonial;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class BookingController extends Controller
{
    public function index()
    {
        $timeSlots = TimeSlot::where('is_active', true)->get();
        $testimonials = Testimonial::active()->take(6)->get();
        $galleryImages = GalleryImage::active()->take(12)->get();
        $videos = GalleryImage::videos()->active()->take(4)->get();

        return inertia('Booking/Index', [
            'timeSlots' => $timeSlots,
            'testimonials' => $testimonials,
            'galleryImages' => $galleryImages,
            'videos' => $videos,
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'time_slot_id' => 'required|exists:time_slots,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'number_of_people' => 'required|integer|min:1|max:20',
            'payment_method' => 'required|in:online,at_site',
            'notes' => 'nullable|string',
        ]);

        $timeSlot = TimeSlot::findOrFail($validated['time_slot_id']);

        // Check availability
        if (!$timeSlot->isAvailableForDate($validated['booking_date'], $validated['number_of_people'])) {
            return back()->withErrors(['error' => 'Not enough slots available for this date.']);
        }

        // Calculate total price
        $totalPrice = $timeSlot->price * $validated['number_of_people'];

        // Create booking
        $booking = Booking::create([
            'time_slot_id' => $validated['time_slot_id'],
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

        // Generate QR code
        $qrData = json_encode([
            'ref' => $booking->booking_reference,
            'date' => $booking->booking_date->format('Y-m-d'),
            'time' => $timeSlot->start_time,
            'people' => $booking->number_of_people,
            'status' => $booking->payment_status,
        ]);

        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($qrData));
        $booking->update(['qr_code' => $qrCode]);

        if ($validated['payment_method'] === 'online') {
            // Create Stripe checkout session
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'River Rafting - ' . $booking->booking_reference,
                            'description' => $booking->number_of_people . ' person(s) on ' . $booking->booking_date->format('M d, Y'),
                        ],
                        'unit_amount' => (int)($totalPrice * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/booking/success/' . $booking->id),
                'cancel_url' => url('/booking/cancel/' . $booking->id),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                ],
            ]);

            return redirect($session->url);
        }

        // For pay at site, show success page directly
        return redirect()->route('booking.confirmation', $booking->id);
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

        return inertia('Booking/Confirmation', [
            'booking' => $booking,
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
}
