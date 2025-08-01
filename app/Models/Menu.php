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

    /**
     * Check if the menu is in stock
     */
    public function isInStock()
    {
        return $this->stock > 0;
    }

    /**
     * Reduce stock by given quantity
     */
    public function reduceStock($quantity)
    {
        if ($this->stock < $quantity) {
            return false;
        }
        
        $this->decrement('stock', $quantity);
        return true;
    }

    /**
     * Increase stock by given quantity
     */
    public function increaseStock($quantity)
    {
        $this->increment('stock', $quantity);
        return true;
    }

    /**
     * Check if menu has sufficient stock for given quantity
     */
    public function hasSufficientStock($quantity)
    {
        return $this->stock >= $quantity;
    }
}
