<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id',
        'payment_gateway',
        'gateway_order_id',
        'gateway_transaction_id',
        'snap_token',
        'payment_method',
        'status',
        'amount',
        'gateway_response',
        'paid_at',
        'expired_at'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return in_array($this->status, ['failed', 'expired', 'cancelled']);
    }

    public function isExpired()
    {
        return $this->expired_at && $this->expired_at < now();
    }

    public function canRetry()
    {
        return $this->isPending() && !$this->isExpired();
    }

    /**
     * Mark payment as expired and restore stock
     */
    public function markAsExpired()
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $this->update(['status' => 'expired']);
        
        // Update transaction status and restore stock
        $transaction = $this->transaction;
        if ($transaction && $transaction->status === 'pending') {
            $transaction->update(['status' => 'cancelled']);
            
            if ($transaction->canRestoreStock()) {
                $transaction->restoreStock();
                \Illuminate\Support\Facades\Log::info('Stock restored for expired payment transaction: ' . $transaction->id);
            }
        }

        return true;
    }

    /**
     * Get expired pending payments
     */
    public static function getExpiredPendingPayments()
    {
        return self::where('status', 'pending')
                   ->where('expired_at', '<', now())
                   ->with('transaction.items.menu')
                   ->get();
    }
}
