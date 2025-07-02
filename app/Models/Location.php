<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = ['name', 'url'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_location');
    }
}
