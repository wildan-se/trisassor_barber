<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{

    /**
     * Step 1: Form buat booking (multi-step via Alpine.js)
     */
    public function create()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Admin tidak diperkenankan melakukan reservasi mandiri.');
        }

        $services = Service::active()->get();
        $barbers  = Barber::active()->get();

        return view('booking.create', compact('services', 'barbers'));
    }

    /**
     * Step 2: Simpan booking baru
     */
    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Admin tidak diperkenankan membuat reservasi pelanggan secara manual dari sini.');
        }

        $validated = $request->validate([
            'service_id'   => 'required|exists:services,id',
            'barber_id'    => 'required|exists:barbers,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'notes'        => 'nullable|string|max:500',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        // Hitung end_time
        $endTime = Carbon::parse($validated['booking_time'])
            ->addMinutes($service->duration_minutes)
            ->format('H:i:s');

        try {
            $booking = \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $service, $endTime) {
                // 1. Kunci (Pessimistic Lock) data Barber ini
                // Request lain yang mencoba booking barber yg sama akan dipaksa menunggu (mengantre).
                $barber = Barber::where('id', $validated['barber_id'])->lockForUpdate()->first();

                // 2. Cek konflik slot (anti double booking) di dalam gembok waktu yang aman
                if (Booking::hasConflict(
                    $validated['barber_id'],
                    $validated['booking_date'],
                    $validated['booking_time'],
                    $endTime
                )) {
                    throw new \Exception('Slot waktu ini baru saja dipesan oleh orang lain. Silakan pilih jadwal yang berbeda.');
                }

                // 3. Simpan jika jadwal benar-benar dipastikan aman dan kosong
                return Booking::create([
                    'user_id'      => Auth::id(),
                    'service_id'   => $validated['service_id'],
                    'barber_id'    => $barber->id,
                    'booking_date' => $validated['booking_date'],
                    'booking_time' => $validated['booking_time'] . ':00',
                    'end_time'     => $endTime,
                    'total_price'  => $service->price,
                    'status'       => 'pending',
                    'notes'        => $validated['notes'] ?? null,
                ]);
            });

            return redirect()->route('booking.show', $booking)
                ->with('success', "Booking berhasil dibuat! Kode: {$booking->booking_code}");

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Booking Error: ' . $e->getMessage());

            // Jika pesan error berasal dari Exception kustom kita (Pessimistic Locking / Conflict)
            $errorMessage = $e->getMessage() === 'Slot waktu ini baru saja dipesan oleh orang lain. Silakan pilih jadwal yang berbeda.'
                ? $e->getMessage()
                : 'Terjadi kesalahan sistem saat memproses booking Anda. Silakan coba lagi beberapa saat.';

            return back()
                ->withInput()
                ->withErrors(['booking_time' => $errorMessage]);
        }
    }

    /**
     * Detail booking customer
     */
    public function show(Booking $booking)
    {
        // Pastikan hanya pemilik booking yang bisa lihat
        abort_if($booking->user_id !== Auth::id(), 403);

        $booking->load(['barber', 'service', 'user']);

        return view('booking.show', compact('booking'));
    }

    /**
     * Riwayat booking customer
     */
    public function index()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Halaman ini khusus untuk riwayat booking pelanggan.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $bookings = $user->bookings()
            ->with(['barber', 'service'])
            ->latest()
            ->paginate(10);

        return view('booking.index', compact('bookings'));
    }

    /**
     * Customer membatalkan booking
     */
    public function cancel(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if (! $booking->canBeCancelled()) {
            return back()->withErrors(['error' => 'Booking ini tidak dapat dibatalkan.']);
        }

        $booking->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
