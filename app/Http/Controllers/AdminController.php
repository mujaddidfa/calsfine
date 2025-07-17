<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Menu;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_orders' => Transaction::count(),
            'pending_orders' => Transaction::where('status', 'pending')->count(),
            'completed_orders' => Transaction::where('status', 'completed')->count(),
            'paid_orders' => Transaction::where('status', 'paid')->count(),
            'cancelled_orders' => Transaction::where('status', 'cancelled')->count(),
            'total_revenue' => Transaction::whereIn('status', ['paid', 'completed'])->sum('total_price'),
            'today_orders' => Transaction::whereDate('order_date', today())->count(),
            'today_revenue' => Transaction::whereDate('order_date', today())
                                        ->whereIn('status', ['paid', 'completed'])
                                        ->sum('total_price'),
        ];

        $recent_orders = Transaction::with(['location'])
            ->orderBy('order_date', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }

    public function orders()
    {
        $orders = Transaction::with(['location'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    public function menus()
    {
        $menus = Menu::with('category')->orderBy('name')->get();
        return view('admin.menus', compact('menus'));
    }
}
