<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Create Snap payment token
     */
    public function createTransaction($transaction)
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $transaction->id . '-' . time(),
                'gross_amount' => (int) $transaction->total_price,
            ],
            'customer_details' => [
                'first_name' => $this->getFirstName($transaction->customer_name),
                'last_name' => $this->getLastName($transaction->customer_name),
                'email' => $transaction->customer_email ?? 'customer@calsfine.com',
                'phone' => $transaction->wa_number,
            ],
            'item_details' => $this->getItemDetails($transaction),
            'callbacks' => [
                'finish' => route('payment.finish'),
            ],
            'expiry' => [
                'duration' => config('services.midtrans.payment_expiry'),
                'unit' => 'minutes'
            ],
            'custom_field1' => $transaction->id, // Store transaction ID for reference
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return [
                'success' => true,
                'snap_token' => $snapToken,
                'redirect_url' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get item details for Midtrans
     */
    private function getItemDetails($transaction)
    {
        $items = [];
        
        foreach ($transaction->items as $item) {
            $items[] = [
                'id' => $item->menu->id,
                'price' => (int) $item->price_per_item,
                'quantity' => (int) $item->qty,
                'name' => $item->menu->name,
                'category' => $item->menu->category ? $item->menu->category->name : 'Food',
            ];
        }

        return $items;
    }

    /**
     * Extract first name from full name
     */
    private function getFirstName($fullName)
    {
        $names = explode(' ', trim($fullName));
        return $names[0] ?? '';
    }

    /**
     * Extract last name from full name
     */
    private function getLastName($fullName)
    {
        $names = explode(' ', trim($fullName));
        if (count($names) > 1) {
            array_shift($names);
            return implode(' ', $names);
        }
        return '';
    }

    /**
     * Verify notification from Midtrans webhook
     */
    public function verifyNotification($notification)
    {
        $serverKey = config('services.midtrans.server_key');
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $grossAmount = $notification['gross_amount'];
        $signatureKey = $notification['signature_key'];

        // Create hash
        $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return $hash === $signatureKey;
    }

    /**
     * Handle payment status
     */
    public function getPaymentStatus($notification)
    {
        $transactionStatus = $notification['transaction_status'];
        $fraudStatus = $notification['fraud_status'] ?? null;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                return 'challenge';
            } else if ($fraudStatus == 'accept') {
                return 'success';
            }
        } else if ($transactionStatus == 'settlement') {
            return 'success';
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            return 'failed';
        } else if ($transactionStatus == 'pending') {
            return 'pending';
        }

        return 'unknown';
    }
}
