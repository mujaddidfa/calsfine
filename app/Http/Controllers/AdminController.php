<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        
        // Calculate H-1 order system stats
        $stats = [
            // Penjualan hari ini (pesanan yang pickup hari ini)
            'today_orders' => Transaction::whereDate('pick_up_date', $today)->count(),
            'today_revenue' => Transaction::whereDate('pick_up_date', $today)
                                        ->whereIn('status', ['paid', 'completed'])
                                        ->sum('total_price'),
            
            // Pesanan yang sudah diambil hari ini (pickup hari ini dan status completed)
            'completed_orders' => Transaction::whereDate('pick_up_date', $today)
                                            ->where('status', 'completed')
                                            ->count(),
            
            // Pesanan belum diambil hari ini (pickup hari ini tapi belum completed)
            'pending_orders' => Transaction::whereDate('pick_up_date', $today)
                                          ->where('status', '!=', 'completed')
                                          ->count(),
            
            // Order H-1 untuk besok (pesanan yang dipesan hari ini untuk pickup besok)
            'tomorrow_orders' => Transaction::whereDate('order_date', $today)
                                           ->whereDate('pick_up_date', $tomorrow)
                                           ->count(),
            
            // Legacy stats for compatibility  
            'total_orders' => Transaction::count(),
        ];
        
        // Calculate pickup rate
        $total_today_orders = $stats['today_orders'];
        $stats['pickup_rate'] = $total_today_orders > 0 
            ? round(($stats['completed_orders'] / $total_today_orders) * 100, 1)
            : 0;

        // Get recent orders for today (pesanan yang pickup hari ini)
        $recent_orders = Transaction::with(['location'])
            ->whereDate('pick_up_date', $today)
            ->orderBy('pick_up_date', 'asc')
            ->take(10)
            ->get();

        // Get tomorrow orders (pesanan yang dipesan hari ini untuk pickup besok)
        $tomorrow_orders = Transaction::with(['location', 'items.menu'])
            ->whereDate('order_date', $today)
            ->whereDate('pick_up_date', $tomorrow)
            ->orderBy('pick_up_date', 'asc')
            ->take(15)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'tomorrow_orders'));
    }

    public function analytics()
    {
        // Get total stats for summary cards (all time data)
        // Hanya hitung pesanan yang sudah selesai prosesnya (completed + wasted)
        $totalOrders = Transaction::whereIn('status', ['completed', 'wasted'])->count();
        $totalRevenue = Transaction::whereIn('status', ['completed'])->sum('total_price'); // Revenue hanya dari completed
        $completedOrders = Transaction::where('status', 'completed')->count(); // Pesanan yang diambil
        $wastedOrders = Transaction::where('status', 'wasted')->count(); // Pesanan yang tidak diambil
        
        $stats = [
            'total_orders' => $totalOrders, // Total pesanan yang selesai (completed + wasted)
            'total_revenue' => $totalRevenue, // Revenue hanya dari pesanan completed
            'completed_orders' => $completedOrders, // Pesanan yang diambil
            'wasted_orders' => $wastedOrders, // Pesanan yang tidak diambil
        ];

        return view('admin.analytics', compact('stats'));
    }

    private function calculateOverallPickupRate()
    {
        // Hanya hitung pesanan yang sudah selesai prosesnya (completed + wasted)
        $totalOrders = Transaction::whereIn('status', ['completed', 'wasted'])->count();
        $completedOrders = Transaction::where('status', 'completed')->count();
        
        return $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
    }

    public function getHistoryData(Request $request)
    {
        $period = $request->get('period', 'daily');
        
        // Debug: Check total transactions in database
        $totalTransactions = Transaction::count();
        Log::info("Total transactions in database: {$totalTransactions}");
        
        $data = [];
        
        switch ($period) {
            case 'daily':
                // Last 30 days dimulai dari kemarin (hari ini dikecualikan karena belum pasti)
                for ($i = 1; $i <= 30; $i++) {
                    $currentDate = now()->subDays($i)->format('Y-m-d');
                    $dayData = $this->getDayStats($currentDate);
                    $data[] = [
                        'date' => now()->subDays($i)->format('d/m/Y'),
                        'orders' => $dayData['orders'],
                        'revenue' => $dayData['revenue'],
                        'completed_orders' => $dayData['completed_orders'], // Kolom "Diambil"
                        'cancelled_orders' => $dayData['cancelled_orders'], // Kolom "Tidak Diambil"
                        'pickup_rate' => $dayData['pickup_rate'],
                        'top_menu' => $dayData['top_menu']
                    ];
                }
                break;
                
            case 'weekly':
                // Last 12 weeks termasuk minggu ini (3 bulan data)
                for ($i = 0; $i < 12; $i++) {
                    $startWeek = now()->subWeeks($i)->startOfWeek();
                    $endWeek = now()->subWeeks($i)->endOfWeek();
                    $weekData = $this->getWeekStats($startWeek, $endWeek);
                    $data[] = [
                        'date' => $startWeek->format('d/m') . ' - ' . $endWeek->format('d/m/Y'),
                        'orders' => $weekData['orders'],
                        'revenue' => $weekData['revenue'],
                        'completed_orders' => $weekData['completed_orders'], // Kolom "Diambil"
                        'cancelled_orders' => $weekData['cancelled_orders'], // Kolom "Tidak Diambil"
                        'pickup_rate' => $weekData['pickup_rate'],
                        'top_menu' => $weekData['top_menu']
                    ];
                }
                break;
                
            case 'monthly':
                // Last 12 months termasuk bulan ini (1 tahun data)
                for ($i = 0; $i < 12; $i++) {
                    $currentMonth = now()->subMonths($i);
                    $monthData = $this->getMonthStats($currentMonth);
                    $data[] = [
                        'date' => $currentMonth->format('F Y'),
                        'orders' => $monthData['orders'],
                        'revenue' => $monthData['revenue'],
                        'completed_orders' => $monthData['completed_orders'], // Kolom "Diambil"
                        'cancelled_orders' => $monthData['cancelled_orders'], // Kolom "Tidak Diambil"
                        'pickup_rate' => $monthData['pickup_rate'],
                        'top_menu' => $monthData['top_menu']
                    ];
                }
                break;
                
            case 'yearly':
                // Last 5 years termasuk tahun ini (karena scope tahunan lebih besar)
                for ($i = 0; $i < 5; $i++) {
                    $currentYear = now()->subYears($i);
                    $yearData = $this->getYearStats($currentYear);
                    $data[] = [
                        'date' => $currentYear->format('Y'),
                        'orders' => $yearData['orders'],
                        'revenue' => $yearData['revenue'],
                        'completed_orders' => $yearData['completed_orders'], // Kolom "Diambil"
                        'cancelled_orders' => $yearData['cancelled_orders'], // Kolom "Tidak Diambil"
                        'pickup_rate' => $yearData['pickup_rate'],
                        'top_menu' => $yearData['top_menu']
                    ];
                }
                break;
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    private function getDayStats($date)
    {
        // Hanya hitung pesanan yang sudah selesai prosesnya (completed + wasted)
        $totalOrders = Transaction::whereDate('order_date', $date)->whereIn('status', ['completed', 'wasted'])->count();
        $completedOrders = Transaction::whereDate('order_date', $date)->where('status', 'completed')->count();
        $cancelledOrders = Transaction::whereDate('order_date', $date)->where('status', 'cancelled')->count();
        $wastedOrders = Transaction::whereDate('order_date', $date)->where('status', 'wasted')->count();
        $revenue = Transaction::whereDate('order_date', $date)->where('status', 'completed')->sum('total_price'); // Revenue hanya dari completed
        
        // Debug logging
        Log::info("Day Stats Debug for {$date}:", [
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'wasted_orders' => $wastedOrders,
            'revenue' => $revenue
        ]);
        
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
            'completed_orders' => $completedOrders, // Kolom "Diambil": status completed
            'cancelled_orders' => $wastedOrders, // Kolom "Tidak Diambil": status wasted saja
            'revenue' => $revenue,
            'pickup_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0,
            'top_menu' => $topMenu ? $topMenu->name : 'N/A'
        ];
    }

    private function getWeekStats($startDate, $endDate)
    {
        // Total pesanan hanya yang completed dan wasted (pesanan yang sudah selesai prosesnya)
        $totalOrders = Transaction::whereBetween('order_date', [$startDate, $endDate])->whereIn('status', ['completed', 'wasted'])->count();
        $completedOrders = Transaction::whereBetween('order_date', [$startDate, $endDate])->where('status', 'completed')->count();
        $cancelledOrders = Transaction::whereBetween('order_date', [$startDate, $endDate])->where('status', 'cancelled')->count();
        $wastedOrders = Transaction::whereBetween('order_date', [$startDate, $endDate])->where('status', 'wasted')->count();
        $revenue = Transaction::whereBetween('order_date', [$startDate, $endDate])->where('status', 'completed')->sum('total_price'); // Revenue hanya dari completed
        
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
            'completed_orders' => $completedOrders, // Kolom "Diambil": status completed
            'cancelled_orders' => $wastedOrders, // Kolom "Tidak Diambil": status wasted saja
            'revenue' => $revenue,
            'pickup_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0,
            'top_menu' => $topMenu ? $topMenu->name : 'N/A'
        ];
    }

    private function getMonthStats($date)
    {
        $startMonth = $date->startOfMonth()->copy();
        $endMonth = $date->endOfMonth()->copy();
        
        // Hanya hitung pesanan yang sudah selesai prosesnya (completed + wasted)
        $totalOrders = Transaction::whereBetween('order_date', [$startMonth, $endMonth])->whereIn('status', ['completed', 'wasted'])->count();
        $completedOrders = Transaction::whereBetween('order_date', [$startMonth, $endMonth])->where('status', 'completed')->count();
        $cancelledOrders = Transaction::whereBetween('order_date', [$startMonth, $endMonth])->where('status', 'cancelled')->count();
        $wastedOrders = Transaction::whereBetween('order_date', [$startMonth, $endMonth])->where('status', 'wasted')->count();
        $revenue = Transaction::whereBetween('order_date', [$startMonth, $endMonth])->where('status', 'completed')->sum('total_price'); // Revenue hanya dari completed
        
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
            'completed_orders' => $completedOrders, // Kolom "Diambil": status completed
            'cancelled_orders' => $wastedOrders, // Kolom "Tidak Diambil": status wasted saja
            'revenue' => $revenue,
            'pickup_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0,
            'top_menu' => $topMenu ? $topMenu->name : 'N/A'
        ];
    }

    private function getYearStats($date)
    {
        $startYear = $date->startOfYear()->copy();
        $endYear = $date->endOfYear()->copy();
        
        // Hanya hitung pesanan yang sudah selesai prosesnya (completed + wasted)
        $totalOrders = Transaction::whereBetween('order_date', [$startYear, $endYear])->whereIn('status', ['completed', 'wasted'])->count();
        $completedOrders = Transaction::whereBetween('order_date', [$startYear, $endYear])->where('status', 'completed')->count();
        $cancelledOrders = Transaction::whereBetween('order_date', [$startYear, $endYear])->where('status', 'cancelled')->count();
        $wastedOrders = Transaction::whereBetween('order_date', [$startYear, $endYear])->where('status', 'wasted')->count();
        $revenue = Transaction::whereBetween('order_date', [$startYear, $endYear])->where('status', 'completed')->sum('total_price'); // Revenue hanya dari completed
        
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
            'completed_orders' => $completedOrders, // Kolom "Diambil": status completed
            'cancelled_orders' => $wastedOrders, // Kolom "Tidak Diambil": status wasted saja
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
