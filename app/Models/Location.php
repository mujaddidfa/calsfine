<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    
    protected $table = 'locations';

    protected $fillable = [
        'name', 'url', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'location_id');
    }

    /**
     * Relationship dengan PickupTime
     */
    public function pickupTimes()
    {
        return $this->hasMany(PickupTime::class, 'location_id');
    }

    /**
     * Pickup times yang aktif
     */
    public function activePickupTimes()
    {
        return $this->hasMany(PickupTime::class, 'location_id')->where('is_active', true)->orderBy('pickup_time');
    }
}
