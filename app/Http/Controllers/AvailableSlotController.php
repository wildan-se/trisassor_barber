<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\BarberSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailableSlotController extends Controller
{
    /**
     * Mengembalikan slot waktu yang tersedia (AJAX)
     *
     * GET /booking/slots?barber_id=1&service_id=2&date=2026-03-30
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'barber_id'  => 'required|exists:barbers,id',
            'service_id' => 'required|exists:services,id',
            'date'       => 'required|date|after_or_equal:today',
        ]);

        $barber   = Barber::findOrFail($request->barber_id);
        $service  = \App\Models\Service::findOrFail($request->service_id);
        $date     = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek; // 0=Minggu, 6=Sabtu

        // Cek jadwal barber di hari tersebut
        $schedule = BarberSchedule::where('barber_id', $barber->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (! $schedule) {
            return response()->json(['slots' => [], 'message' => 'Barber tidak buka pada hari ini.']);
        }

        // Generate semua slot
        $allSlots = $schedule->generateSlots($service->duration_minutes);

        // Ambil booking yang sudah ada di hari tersebut untuk barber ini
        $existingBookings = Booking::where('barber_id', $barber->id)
            ->where('booking_date', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['booking_time', 'end_time']);

        // Filter slot yang ada konflik
        $availableSlots = array_filter($allSlots, function ($slot) use ($existingBookings, $service, $date) {
            // Jangan tampilkan slot yang sudah lewat hari ini
            if ($date->isToday()) {
                $slotTime = Carbon::parse($slot);
                if ($slotTime->lte(Carbon::now()->addMinutes(30))) {
                    return false;
                }
            }

            $slotStart = strtotime($slot);
            $slotEnd   = $slotStart + ($service->duration_minutes * 60);

            foreach ($existingBookings as $booking) {
                $bookingStart = strtotime($booking->booking_time);
                $bookingEnd   = strtotime($booking->end_time);

                // Cek overlap
                if ($slotStart < $bookingEnd && $slotEnd > $bookingStart) {
                    return false;
                }
            }

            return true;
        });

        return response()->json([
            'slots'   => array_values($availableSlots),
            'message' => count($availableSlots) > 0 ? 'ok' : 'Tidak ada slot tersedia.',
        ]);
    }
}
