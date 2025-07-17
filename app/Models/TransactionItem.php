<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $table = 'transaction_items';
    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 'menu_id', 'qty',
        'price_per_item', 'total_price'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
