<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\Payment;
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

        // Find payment by gateway_order_id
        $payment = Payment::where('gateway_order_id', $orderId)->first();
        if (!$payment) {
            Log::error('Payment not found for order_id: ' . $orderId);
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        // Get payment status
        $paymentStatus = $this->midtransService->getPaymentStatus($notification);
        
        // Update payment based on status
        $this->updatePaymentStatus($payment, $notification, $paymentStatus);

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
     * Update payment status based on payment result
     */
    private function updatePaymentStatus($payment, $notification, $paymentStatus)
    {
        $updateData = [
            'gateway_transaction_id' => $notification['transaction_id'] ?? null,
            'payment_method' => $notification['payment_type'] ?? null,
            'gateway_response' => $notification,
        ];

        switch ($paymentStatus) {
            case 'success':
                $updateData['status'] = 'success';
                $updateData['paid_at'] = now()->setTimezone('Asia/Jakarta');
                
                // Update transaction status to paid
                $payment->transaction->update(['status' => 'paid']);
                
                Log::info('Payment successful for transaction: ' . $payment->transaction_id);
                break;
                
            case 'pending':
                $updateData['status'] = 'pending';
                Log::info('Payment pending for transaction: ' . $payment->transaction_id);
                break;
                
            case 'failed':
                $updateData['status'] = 'failed';
                
                // Update transaction status to cancelled
                $payment->transaction->update(['status' => 'cancelled']);
                
                Log::info('Payment failed for transaction: ' . $payment->transaction_id);
                break;
                
            case 'challenge':
                $updateData['status'] = 'challenge';
                Log::info('Payment under fraud review for transaction: ' . $payment->transaction_id);
                break;
                
            default:
                Log::warning('Unknown payment status: ' . $paymentStatus . ' for transaction: ' . $payment->transaction_id);
        }

        $payment->update($updateData);
    }

    /**
     * Check payment status manually
     */
    public function checkStatus($transactionId)
    {
        $transaction = Transaction::with('latestPayment')->find($transactionId);
        
        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
        }

        $payment = $transaction->latestPayment;

        return response()->json([
            'status' => 'success',
            'transaction_id' => $transaction->id,
            'payment_status' => $transaction->status,
            'payment_gateway_status' => $payment ? $payment->status : 'unpaid',
            'payment_method' => $payment ? $payment->payment_method : null,
            'paid_at' => $payment && $payment->paid_at ? $payment->paid_at->toISOString() : null,
        ]);
    }
}
