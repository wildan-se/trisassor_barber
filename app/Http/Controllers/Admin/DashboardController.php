<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_bookings'     => Booking::count(),
            'pending_bookings'   => Booking::pending()->count(),
            'confirmed_bookings' => Booking::confirmed()->count(),
            'today_bookings'     => Booking::whereDate('booking_date', today())->count(),
            'total_revenue'      => Booking::completed()->sum('total_price'),
            'this_month_revenue' => Booking::completed()
                ->whereMonth('booking_date', now()->month)
                ->sum('total_price'),
            'total_barbers'      => Barber::active()->count(),
            'total_services'     => Service::active()->count(),
        ];

        // Booking terbaru (5)
        $recentBookings = Booking::with(['user', 'barber', 'service'])
            ->latest()
            ->take(5)
            ->get();

        // Booking hari ini
        $todayBookings = Booking::with(['user', 'barber', 'service'])
            ->whereDate('booking_date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('booking_time')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'todayBookings'));
    }
}
