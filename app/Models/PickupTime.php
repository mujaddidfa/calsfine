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
        'pickup_time'
    ];

    /**
     * Relationship dengan Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
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
        $value = isset($this->attributes['pickup_time']) ? $this->attributes['pickup_time'] : null;
        if (!$value) return null;
        // Jika string (misal: '14:00:00'), ambil jam dan menit saja
        return substr($value, 0, 5);
    }
}
