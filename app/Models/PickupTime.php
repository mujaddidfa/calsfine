<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupTime extends Model
{
    use HasFactory;

    protected $table = 'pickup_times';

    protected $fillable = [
        'location_id',
        'pickup_time',
        'is_active'
    ];

    protected $casts = [
        'pickup_time' => 'datetime:H:i', // Cast ke format waktu HH:MM
        'is_active' => 'boolean'
    ];

    /**
     * Relationship dengan Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * Scope untuk pickup time yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk pickup time berdasarkan lokasi
     */
    public function scopeForLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    /**
     * Format waktu untuk display
     */
    public function getFormattedTimeAttribute()
    {
        return $this->pickup_time ? $this->pickup_time->format('H:i') : null;
    }
}
