<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'photo',
        'specialty',
        'bio',
        'instagram',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Auto-generate slug ─────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function ($barber) {
            if (empty($barber->slug)) {
                $barber->slug = Str::slug($barber->name);
            }
        });
    }

    // ─── Relations ──────────────────────────────────────────
    public function schedules()
    {
        return $this->hasMany(BarberSchedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ─── Scopes ─────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ────────────────────────────────────────────
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=C5A059&color=0A0A0A&size=200';
    }
}
