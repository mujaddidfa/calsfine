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
}
