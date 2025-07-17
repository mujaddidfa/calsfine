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
        'category_id', 'image', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
