<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Menu;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        
        // Calculate H-1 order system stats
        $stats = [
            // Penjualan hari ini (dari order H-1 kemarin)
            'today_orders' => Transaction::whereDate('order_date', $today)->count(),
            'today_revenue' => Transaction::whereDate('order_date', $today)
                                        ->whereIn('status', ['paid', 'completed'])
                                        ->sum('total_price'),
            
            // Pesanan yang sudah diambil hari ini
            'completed_orders' => Transaction::whereDate('order_date', $today)
                                            ->where('status', 'completed')
                                            ->count(),
            
            // Pesanan belum diambil hari ini (semua status selain completed)
            'pending_orders' => Transaction::whereDate('order_date', $today)
                                          ->where('status', '!=', 'completed')
                                          ->count(),
            
            // Order H-1 untuk besok
            'tomorrow_orders' => Transaction::whereDate('order_date', $tomorrow)->count(),
            
            // Legacy stats for compatibility  
            'total_orders' => Transaction::count(),
        ];
        
        // Calculate pickup rate
        $total_today_orders = $stats['today_orders'];
        $stats['pickup_rate'] = $total_today_orders > 0 
            ? round(($stats['completed_orders'] / $total_today_orders) * 100, 1)
            : 0;

        // Get recent orders for today (H-1 orders from yesterday)
        $recent_orders = Transaction::with(['location'])
            ->whereDate('order_date', $today)
            ->orderBy('pick_up_date', 'asc')
            ->take(10)
            ->get();

        // Get tomorrow orders (H-1 orders placed today for tomorrow)
        $tomorrow_orders = Transaction::with(['location', 'items.menu'])
            ->whereDate('order_date', $tomorrow)
            ->orderBy('pick_up_date', 'asc')
            ->take(15)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'tomorrow_orders'));
    }

    public function analytics()
    {
        // Get basic stats for analytics page
        $stats = [
            'total_orders' => Transaction::count(),
            'today_revenue' => Transaction::whereDate('order_date', now()->format('Y-m-d'))
                                        ->whereIn('status', ['paid', 'completed'])
                                        ->sum('total_price'),
            'pickup_rate' => $this->calculateOverallPickupRate(),
        ];

        return view('admin.analytics', compact('stats'));
    }

    private function calculateOverallPickupRate()
    {
        $totalOrders = Transaction::count();
        $completedOrders = Transaction::where('status', 'completed')->count();
        
        return $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
    }

    public function getHistoryData(Request $request)
    {
        $period = $request->get('period', 'daily');
        $date = $request->get('date', now()->format('Y-m-d'));
        
        $data = [];
        
        switch ($period) {
            case 'daily':
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $currentDate = now()->subDays($i)->format('Y-m-d');
                    $dayData = $this->getDayStats($currentDate);
                    $data[] = [
                        'date' => now()->subDays($i)->format('d/m/Y'),
                        'orders' => $dayData['orders'],
                        'revenue' => $dayData['revenue'],
                        'pickup_rate' => $dayData['pickup_rate'],
                        'top_menu' => $dayData['top_menu'],
                        'status' => $dayData['pickup_rate'] >= 80 ? 'success' : 'warning'
                    ];
                }
                break;
                
            case 'weekly':
                // Last 4 weeks
                for ($i = 3; $i >= 0; $i--) {
                    $startWeek = now()->subWeeks($i)->startOfWeek();
                    $endWeek = now()->subWeeks($i)->endOfWeek();
                    $weekData = $this->getWeekStats($startWeek, $endWeek);
                    $data[] = [
                        'date' => $startWeek->format('d/m') . ' - ' . $endWeek->format('d/m/Y'),
                        'orders' => $weekData['orders'],
                        'revenue' => $weekData['revenue'],
                        'pickup_rate' => $weekData['pickup_rate'],
                        'top_menu' => $weekData['top_menu'],
                        'status' => $weekData['pickup_rate'] >= 80 ? 'success' : 'warning'
                    ];
                }
                break;
                
            case 'monthly':
                // Last 6 months
                for ($i = 5; $i >= 0; $i--) {
                    $currentMonth = now()->subMonths($i);
                    $monthData = $this->getMonthStats($currentMonth);
                    $data[] = [
                        'date' => $currentMonth->format('F Y'),
                        'orders' => $monthData['orders'],
                        'revenue' => $monthData['revenue'],
                        'pickup_rate' => $monthData['pickup_rate'],
                        'top_menu' => $monthData['top_menu'],
                        'status' => $monthData['pickup_rate'] >= 80 ? 'success' : 'warning'
                    ];
                }
                break;
                
            case 'yearly':
                // Last 3 years
                for ($i = 2; $i >= 0; $i--) {
                    $currentYear = now()->subYears($i);
                    $yearData = $this->getYearStats($currentYear);
                    $data[] = [
                        'date' => $currentYear->format('Y'),
                        'orders' => $yearData['orders'],
                        'revenue' => $yearData['revenue'],
                        'pickup_rate' => $yearData['pickup_rate'],
                        'top_menu' => $yearData['top_menu'],
                        'status' => $yearData['pickup_rate'] >= 80 ? 'success' : 'warning'
                    ];
                }
                break;
        }
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'summary' => [
                'total_orders' => collect($data)->sum('orders'),
                'total_revenue' => collect($data)->sum('revenue'),
                'avg_pickup_rate' => collect($data)->avg('pickup_rate'),
                'period' => $period
            ]
        ]);
    }

    private function getDayStats($date)
    {
        $orders = Transaction::whereDate('order_date', $date);
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $revenue = $orders->whereIn('status', ['paid', 'completed'])->sum('total_price');
        
        // Get top menu for the day
        $topMenu = Transaction::whereDate('order_date', $date)
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('menus', 'transaction_items.menu_id', '=', 'menus.id')
            ->selectRaw('menus.name, SUM(transaction_items.qty) as total_qty')
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_qty', 'desc')
            ->first();
        
        return [
            'orders' => $totalOrders,
            'revenue' => $revenue,
            'pickup_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0,
            'top_menu' => $topMenu ? $topMenu->name : 'N/A'
        ];
    }

    private function getWeekStats($startDate, $endDate)
    {
        $orders = Transaction::whereBetween('order_date', [$startDate, $endDate]);
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $revenue = $orders->whereIn('status', ['paid', 'completed'])->sum('total_price');
        
        // Get top menu for the week
        $topMenu = Transaction::whereBetween('order_date', [$startDate, $endDate])
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('menus', 'transaction_items.menu_id', '=', 'menus.id')
            ->selectRaw('menus.name, SUM(transaction_items.qty) as total_qty')
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_qty', 'desc')
            ->first();
        
        return [
            'orders' => $totalOrders,
            'revenue' => $revenue,
            'pickup_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0,
            'top_menu' => $topMenu ? $topMenu->name : 'N/A'
        ];
    }

    private function getMonthStats($date)
    {
        $startMonth = $date->startOfMonth()->copy();
        $endMonth = $date->endOfMonth()->copy();
        
        $orders = Transaction::whereBetween('order_date', [$startMonth, $endMonth]);
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $revenue = $orders->whereIn('status', ['paid', 'completed'])->sum('total_price');
        
        // Get top menu for the month
        $topMenu = Transaction::whereBetween('order_date', [$startMonth, $endMonth])
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('menus', 'transaction_items.menu_id', '=', 'menus.id')
            ->selectRaw('menus.name, SUM(transaction_items.qty) as total_qty')
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_qty', 'desc')
            ->first();
        
        return [
            'orders' => $totalOrders,
            'revenue' => $revenue,
            'pickup_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0,
            'top_menu' => $topMenu ? $topMenu->name : 'N/A'
        ];
    }

    private function getYearStats($date)
    {
        $startYear = $date->startOfYear()->copy();
        $endYear = $date->endOfYear()->copy();
        
        $orders = Transaction::whereBetween('order_date', [$startYear, $endYear]);
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $revenue = $orders->whereIn('status', ['paid', 'completed'])->sum('total_price');
        
        // Get top menu for the year
        $topMenu = Transaction::whereBetween('order_date', [$startYear, $endYear])
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('menus', 'transaction_items.menu_id', '=', 'menus.id')
            ->selectRaw('menus.name, SUM(transaction_items.qty) as total_qty')
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_qty', 'desc')
            ->first();
        
        return [
            'orders' => $totalOrders,
            'revenue' => $revenue,
            'pickup_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0,
            'top_menu' => $topMenu ? $topMenu->name : 'N/A'
        ];
    }

    public function orders()
    {
        $orders = Transaction::with(['location'])
            ->orderBy('order_date', 'desc')
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    public function menus()
    {
        $menus = Menu::with('category')->orderBy('name')->get();
        return view('admin.menus', compact('menus'));
    }
}
