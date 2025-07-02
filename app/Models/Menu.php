<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    
    protected $table = 'menus';

    protected $fillable = [
        'name', 'price', 'stock', 'description',
        'id_category', 'photo', 'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
}
