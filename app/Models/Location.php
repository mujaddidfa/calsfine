<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    
    protected $table = 'locations';

    protected $fillable = [
        'name', 'address', 'contact_person', 
        'contact_phone', 'operating_hours', 'url', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'location_id');
    }
}
