<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    
    // Disable timestamps karena table tidak punya created_at, updated_at
    public $timestamps = false;

    protected $fillable = [
        'customer_name', 'wa_number', 'note',
        'order_date', 'pick_up_date', 'location_id',
        'total_price', 'status', 'payment_date',
        'qris_reference', 'qris_expiry'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'pick_up_date' => 'datetime',
        'payment_date' => 'datetime',
        'qris_expiry' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
