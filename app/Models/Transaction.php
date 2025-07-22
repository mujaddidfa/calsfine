<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    protected $table = 'transactions';
    
    // Disable timestamps karena table tidak punya created_at, updated_at
    public $timestamps = false;

    protected $fillable = [
        'pickup_code', 'customer_name', 'wa_number', 'customer_email', 'note',
        'order_date', 'pick_up_date', 'location_id',
        'total_price', 'status'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'pick_up_date' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latest();
    }

    public function successfulPayment()
    {
        return $this->hasOne(Payment::class)->where('status', 'success');
    }

    // Transaction status methods
    public function isPaid()
    {
        return $this->successfulPayment()->exists();
    }

    public function getPaymentStatus()
    {
        $payment = $this->latestPayment;
        return $payment ? $payment->status : 'unpaid';
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

    /**
     * Update stock for all items in this transaction
     * Should be called when payment is successful
     */
    public function updateMenuStock()
    {
        foreach ($this->items as $item) {
            $menu = $item->menu;
            if ($menu) {
                // Reduce stock by the quantity ordered
                $newStock = $menu->stock - $item->qty;
                
                // Ensure stock doesn't go below 0
                $menu->stock = max(0, $newStock);
                $menu->save();
                
                Log::info("Stock updated for menu: {$menu->name}", [
                    'menu_id' => $menu->id,
                    'old_stock' => $menu->stock + $item->qty,
                    'quantity_sold' => $item->qty,
                    'new_stock' => $menu->stock,
                    'transaction_id' => $this->getKey()
                ]);
            }
        }
    }

    /**
     * Restore stock for all items in this transaction
     * Should be called when payment fails or transaction is cancelled
     */
    public function restoreMenuStock()
    {
        foreach ($this->items as $item) {
            $menu = $item->menu;
            if ($menu) {
                // Add back the stock
                $menu->stock += $item->qty;
                $menu->save();
                
                Log::info("Stock restored for menu: {$menu->name}", [
                    'menu_id' => $menu->id,
                    'quantity_restored' => $item->qty,
                    'new_stock' => $menu->stock,
                    'transaction_id' => $this->getKey()
                ]);
            }
        }
    }
}
