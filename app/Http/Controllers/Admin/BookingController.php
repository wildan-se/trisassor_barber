<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'barber', 'service'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            )->orWhere('booking_code', 'like', "%{$request->search}%");
        }

        $bookings = $query->paginate(20)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'barber', 'service']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking dengan status "Menunggu" yang bisa dikonfirmasi.');
        }

        try {
            $booking->update([
                'status'       => 'confirmed',
                'confirmed_at' => now(),
            ]);

            return back()->with('success', "Booking {$booking->booking_code} berhasil dikonfirmasi.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin Confirm Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat mengkonfirmasi booking.');
        }
    }

    public function complete(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Hanya booking yang telah dikonfirmasi yang bisa diselesaikan.');
        }

        try {
            $booking->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);

            return back()->with('success', "Booking {$booking->booking_code} berhasil diselesaikan.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin Complete Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menyelesaikan booking.');
        }
    }

    public function cancel(Booking $booking, Request $request)
    {
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Booking ini sudah selesai atau dibatalkan dan tidak bisa diubah lagi.');
        }

        $request->validate(['admin_notes' => 'nullable|string|max:500']);

        try {
            $booking->update([
                'status'       => 'cancelled',
                'admin_notes'  => $request->admin_notes,
                'cancelled_at' => now(),
            ]);

            return back()->with('success', "Booking {$booking->booking_code} berhasil dibatalkan.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin Cancel Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat membatalkan booking.');
        }
    }
}
