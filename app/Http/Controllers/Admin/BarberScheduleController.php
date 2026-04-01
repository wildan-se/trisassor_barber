<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\BarberSchedule;
use Illuminate\Http\Request;

class BarberScheduleController extends Controller
{
    public function index()
    {
        $barbers = Barber::active()->with('schedules')->get();
        return view('admin.schedules.index', compact('barbers'));
    }

    public function update(Request $request, Barber $barber)
    {
        $validated = $request->validate([
            'schedules'                   => 'required|array',
            'schedules.*.day_of_week'     => 'required|integer|between:0,6',
            'schedules.*.start_time'      => 'required|date_format:H:i',
            'schedules.*.end_time'        => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.is_available'    => 'boolean',
        ]);

        foreach ($validated['schedules'] as $scheduleData) {
            BarberSchedule::updateOrCreate(
                [
                    'barber_id'  => $barber->id,
                    'day_of_week' => $scheduleData['day_of_week'],
                ],
                [
                    'start_time'   => $scheduleData['start_time'] . ':00',
                    'end_time'     => $scheduleData['end_time'] . ':00',
                    'is_available' => $scheduleData['is_available'] ?? true,
                ]
            );
        }

        return back()->with('success', "Jadwal {$barber->name} berhasil diperbarui.");
    }
}
