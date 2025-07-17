<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories';

    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'category_id');
    }
}
