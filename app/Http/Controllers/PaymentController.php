<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Services\MidtransService;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle Midtrans notification webhook
     */
    public function notification(Request $request)
    {
        $notification = $request->all();
        
        Log::info('Midtrans Notification:', $notification);

        // Verify notification
        if (!$this->midtransService->verifyNotification($notification)) {
            Log::error('Invalid Midtrans notification signature');
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        // Extract transaction ID from order_id
        $orderId = $notification['order_id'];
        $transactionId = $this->extractTransactionId($orderId);
        
        if (!$transactionId) {
            Log::error('Unable to extract transaction ID from order_id: ' . $orderId);
            return response()->json(['status' => 'error', 'message' => 'Invalid order ID'], 400);
        }

        // Find transaction
        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            Log::error('Transaction not found: ' . $transactionId);
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
        }

        // Get payment status
        $paymentStatus = $this->midtransService->getPaymentStatus($notification);
        
        // Update transaction based on payment status
        $this->updateTransactionStatus($transaction, $notification, $paymentStatus);

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle payment finish callback (user redirected here after payment)
     */
    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');
        $statusCode = $request->get('status_code');
        $transactionStatus = $request->get('transaction_status');

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            return view('payment.success', compact('orderId'));
        } else if ($transactionStatus == 'pending') {
            return view('payment.pending', compact('orderId'));
        } else {
            return view('payment.failed', compact('orderId'));
        }
    }

    /**
     * Extract transaction ID from Midtrans order_id
     */
    private function extractTransactionId($orderId)
    {
        // ORDER-{transaction_id}-{timestamp} format
        $parts = explode('-', $orderId);
        return isset($parts[1]) ? (int)$parts[1] : null;
    }

    /**
     * Update transaction status based on payment result
     */
    private function updateTransactionStatus($transaction, $notification, $paymentStatus)
    {
        $updateData = [
            'midtrans_transaction_id' => $notification['transaction_id'] ?? null,
            'midtrans_transaction_status' => $notification['transaction_status'] ?? null,
            'payment_method' => $notification['payment_type'] ?? null,
        ];

        switch ($paymentStatus) {
            case 'success':
                $updateData['status'] = 'paid';
                $updateData['payment_date'] = now()->setTimezone('Asia/Jakarta');
                Log::info('Payment successful for transaction: ' . $transaction->id);
                break;
                
            case 'pending':
                $updateData['status'] = 'pending';
                Log::info('Payment pending for transaction: ' . $transaction->id);
                break;
                
            case 'failed':
                $updateData['status'] = 'cancelled';
                Log::info('Payment failed for transaction: ' . $transaction->id);
                break;
                
            case 'challenge':
                // Keep as pending until fraud review
                Log::info('Payment under fraud review for transaction: ' . $transaction->id);
                break;
                
            default:
                Log::warning('Unknown payment status: ' . $paymentStatus . ' for transaction: ' . $transaction->id);
        }

        $transaction->update($updateData);
    }

    /**
     * Check payment status manually
     */
    public function checkStatus($transactionId)
    {
        $transaction = Transaction::find($transactionId);
        
        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'transaction_id' => $transaction->id,
            'payment_status' => $transaction->status,
            'midtrans_status' => $transaction->midtrans_transaction_status,
            'payment_method' => $transaction->payment_method,
        ]);
    }
}
