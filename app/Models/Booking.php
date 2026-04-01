<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'barber_id',
        'service_id',
        'booking_date',
        'booking_time',
        'end_time',
        'total_price',
        'status',
        'notes',
        'admin_notes',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'booking_date'  => 'date',
        'confirmed_at'  => 'datetime',
        'completed_at'  => 'datetime',
        'cancelled_at'  => 'datetime',
    ];

    // ─── Boot: generate booking_code ────────────────────────
    protected static function booted(): void
    {
        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $date   = Carbon::now()->format('Ymd');
                $count  = self::whereDate('created_at', today())->count() + 1;
                $booking->booking_code = 'TRS-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            // Hitung end_time otomatis dari service
            if (empty($booking->end_time) && $booking->service_id && $booking->booking_time) {
                $service = Service::find($booking->service_id);
                if ($service) {
                    $booking->end_time = Carbon::parse($booking->booking_time)
                        ->addMinutes($service->duration_minutes)
                        ->format('H:i:s');
                }
            }
        });
    }

    // ─── Relations ──────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // ─── Scopes ─────────────────────────────────────────────
    public function scopePending($query)      { return $query->where('status', 'pending'); }
    public function scopeConfirmed($query)    { return $query->where('status', 'confirmed'); }
    public function scopeCompleted($query)    { return $query->where('status', 'completed'); }
    public function scopeCancelled($query)    { return $query->where('status', 'cancelled'); }

    // ─── Anti Double-Booking Check ───────────────────────────
    public static function hasConflict(int $barberId, string $date, string $time, string $endTime, ?int $excludeId = null): bool
    {
        return self::where('barber_id', $barberId)
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($time, $endTime) {
                // Cek overlap: booking baru mulai sebelum booking lama selesai
                // dan booking baru selesai setelah booking lama mulai
                $q->where(function ($inner) use ($time, $endTime) {
                    $inner->where('booking_time', '<', $endTime)
                          ->where('end_time', '>', $time);
                });
            })
            ->exists();
    }

    // ─── Helpers ────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'   => '<span class="badge-pending">Menunggu</span>',
            'confirmed' => '<span class="badge-confirmed">Dikonfirmasi</span>',
            'completed' => '<span class="badge-completed">Selesai</span>',
            'cancelled' => '<span class="badge-cancelled">Dibatalkan</span>',
            default     => $this->status,
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function canBeCancelled(): bool
    {
        if (!in_array($this->status, ['pending', 'confirmed'])) {
            return false;
        }

        $bookingDateTime = Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $this->booking_time);
        return $bookingDateTime->isAfter(now());
    }
}
