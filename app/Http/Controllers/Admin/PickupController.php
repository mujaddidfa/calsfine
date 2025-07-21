<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickupController extends Controller
{
    /**
     * Scan QR Code untuk pickup pesanan
     */
    public function scan($id)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::with(['items.menu', 'location'])->findOrFail($id);
            
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
                ->with('success', 'Pesanan berhasil diselesaikan! Customer: ' . $transaction->customer_name);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'QR Code tidak valid atau pesanan tidak ditemukan');
        }
    }
    
    /**
     * API endpoint untuk scan via AJAX
     */
    public function apiScan($id)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::with(['items.menu', 'location'])->findOrFail($id);
            
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
                    'order_id' => $transaction->id
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau pesanan tidak ditemukan'
            ], 404);
        }
    }
}
