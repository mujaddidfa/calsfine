<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Location;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of today's orders for pickup management.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        
        $query = Transaction::with(['location', 'items.menu'])
            ->whereDate('pick_up_date', $today);

        $orders = $query->orderBy('pick_up_date', 'asc')->paginate(20);
        $locations = Location::where('is_active', 1)->get();

        return view('admin.orders.index', compact('orders', 'locations'));
    }

    /**
     * Display tomorrow's orders for preparation.
     */
    public function tomorrow()
    {
        $tomorrow = Carbon::tomorrow();
        
        $orders = Transaction::with(['location', 'items.menu'])
            ->whereDate('pick_up_date', $tomorrow)
            ->where('status', 'paid')
            ->orderBy('pick_up_date', 'asc')
            ->paginate(20);

        return view('admin.orders.tomorrow', compact('orders'));
    }

    /**
     * Show the specified order details.
     */
    public function show(Transaction $order)
    {
        $order->load(['location', 'items.menu']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of an order from the detail page.
     */
    public function updateStatus(Request $request, Transaction $order)
    {
        $request->validate([
            'status' => 'required|in:paid,completed,cancelled,wasted',
        ]);

        $status = $request->input('status');
        $updateData = ['status' => $status];

        $order->update($updateData);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
