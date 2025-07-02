<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories';

    protected $fillable = ['name', 'description', 'is_active'];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'id_category');
    }
}
