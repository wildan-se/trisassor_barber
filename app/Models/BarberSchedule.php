<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'barber_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    const DAYS = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    // ─── Relations ──────────────────────────────────────────
    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    // ─── Helpers ────────────────────────────────────────────
    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Generate time slots (setiap 30 menit) antara start_time dan end_time.
     */
    public function generateSlots(int $durationMinutes = 60): array
    {
        $slots = [];
        $start = strtotime($this->start_time);
        $end   = strtotime($this->end_time) - ($durationMinutes * 60);

        while ($start <= $end) {
            $slots[] = date('H:i', $start);
            $start += 30 * 60; // interval 30 menit
        }

        return $slots;
    }
}
