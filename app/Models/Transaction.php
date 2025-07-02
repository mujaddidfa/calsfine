<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    public $timestamps = false;

    protected $fillable = [
        'customer_name', 'email', 'wa_number', 'note',
        'order_date', 'pick_up_date', 'id_location',
        'total_price', 'status', 'payment_date',
        'qris_reference', 'qris_expiry'
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'id_transaction');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location');
    }
}
