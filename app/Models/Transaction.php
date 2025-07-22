<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    
    // Disable timestamps karena table tidak punya created_at, updated_at
    public $timestamps = false;

    protected $fillable = [
        'pickup_code', 'customer_name', 'wa_number', 'customer_email', 'note',
        'order_date', 'pick_up_date', 'location_id',
        'total_price', 'status', 'payment_date',
        'qris_reference', 'qris_expiry',
        'midtrans_order_id', 'midtrans_snap_token', 'midtrans_transaction_id',
        'midtrans_transaction_status', 'payment_method'
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

    /**
     * Generate unique pickup code
     */
    public static function generatePickupCode()
    {
        do {
            // Generate 8 character alphanumeric code
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (self::where('pickup_code', $code)->exists());

        return $code;
    }

    /**
     * Boot method to auto-generate pickup code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->pickup_code)) {
                $transaction->pickup_code = self::generatePickupCode();
            }
        });
    }
}
