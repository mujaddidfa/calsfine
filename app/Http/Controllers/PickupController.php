<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickupController extends Controller
{
    /**
     * Scan QR Code untuk pickup pesanan
     */
    public function scan($code)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::with(['items.menu', 'location'])
                                    ->where('pickup_code', $code)
                                    ->firstOrFail();
            
            // Cek apakah pesanan masih pending atau sudah paid
            if (!in_array($transaction->status, ['pending', 'paid'])) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Pesanan ini sudah ' . $transaction->status);
            }
            
            // Update status menjadi completed
            $transaction->update([
                'status' => 'completed'
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('success', 'Pesanan berhasil diselesaikan! Customer: ' . $transaction->customer_name . ' (Code: ' . $code . ')');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Pickup code tidak valid atau pesanan tidak ditemukan');
        }
    }
    
    /**
     * API endpoint untuk scan via AJAX
     */
    public function apiScan($code)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::with(['items.menu', 'location'])
                                    ->where('pickup_code', $code)
                                    ->firstOrFail();
            
            // Cek apakah pesanan masih pending atau sudah paid
            if (!in_array($transaction->status, ['pending', 'paid'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan ini sudah ' . $transaction->status
                ], 400);
            }
            
            // Update status menjadi completed
            $transaction->update([
                'status' => 'completed'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diselesaikan!',
                'data' => [
                    'customer_name' => $transaction->customer_name,
                    'total_price' => $transaction->total_price,
                    'order_id' => $transaction->id,
                    'pickup_code' => $code
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pickup code tidak valid atau pesanan tidak ditemukan'
            ], 404);
        }
    }
}
