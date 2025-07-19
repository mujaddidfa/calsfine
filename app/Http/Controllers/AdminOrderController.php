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

        // Filter by status if requested
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by location if requested
        if ($request->has('location') && $request->location !== 'all') {
            $query->where('location_id', $request->location);
        }

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
     * Mark order as completed (picked up).
     */
    public function markCompleted(Transaction $order)
    {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Hanya pesanan yang sudah dibayar yang dapat ditandai sebagai selesai!');
        }

        $order->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return back()->with('success', "Pesanan #{$order->order_number} berhasil ditandai sebagai diambil!");
    }

    /**
     * Mark order as cancelled/wasted (not picked up).
     */
    public function markCancelled(Transaction $order)
    {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Hanya pesanan yang sudah dibayar yang dapat dibatalkan!');
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => 'Tidak diambil customer'
        ]);

        return back()->with('success', "Pesanan #{$order->order_number} berhasil ditandai sebagai tidak diambil!");
    }

    /**
     * Bulk update order statuses.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:transactions,id',
            'action' => 'required|in:completed,cancelled'
        ]);

        $orders = Transaction::whereIn('id', $request->order_ids)
            ->where('status', 'paid')
            ->get();

        $updated = 0;
        foreach ($orders as $order) {
            if ($request->action === 'completed') {
                $order->update([
                    'status' => 'completed',
                    'completed_at' => now()
                ]);
            } else {
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Tidak diambil customer'
                ]);
            }
            $updated++;
        }

        $action = $request->action === 'completed' ? 'selesai' : 'dibatalkan';
        return back()->with('success', "{$updated} pesanan berhasil ditandai sebagai {$action}!");
    }

    /**
     * Generate pickup report for specific date.
     */
    public function report(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        
        $orders = Transaction::with(['location', 'items.menu'])
            ->whereDate('pick_up_date', $date)
            ->get();

        $statistics = [
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            'pending_orders' => $orders->where('status', 'paid')->count(),
            'total_revenue' => $orders->where('status', 'completed')->sum('total_amount'),
            'pickup_rate' => $orders->count() > 0 ? ($orders->where('status', 'completed')->count() / $orders->count()) * 100 : 0
        ];

        return view('admin.orders.report', compact('orders', 'statistics', 'date'));
    }
}
