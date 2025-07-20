<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Menu;
use App\Models\Location;
use App\Models\PickupTime;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil semua menu yang aktif dengan kategorinya
        $menus = Menu::where('is_active', 1)
            ->with('category')
            ->get();

        // Ambil semua lokasi (hapus filter is_active untuk debugging)
        $locations = Location::all();

        // Ambil semua pickup times dengan relasi location
        $pickupTimes = PickupTime::with('location')->get();

        return view('order', compact('menus', 'locations', 'pickupTimes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:50',
            'wa_number' => 'required|string|max:20',
            'note' => 'nullable|string',
            'pick_up_date' => 'required|date',
            'pickup_time' => 'required|string', // Format HH:MM
            'id_location' => 'required|exists:locations,id',
            'items' => 'required|array|min:1',
            'items.*.id_menu' => 'required|exists:menus,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $menu = Menu::findOrFail($item['id_menu']);
                $subtotal = $menu->price * $item['qty'];
                $total += $subtotal;

                $items[] = [
                    'id_menu' => $menu->id,
                    'qty' => $item['qty'],
                    'price_per_item' => $menu->price,
                    'total_price' => $subtotal,
                ];
            }

            // Gabungkan tanggal dan waktu pickup
            $pickupDateTime = $validated['pick_up_date'] . ' ' . $validated['pickup_time'] . ':00';

            $transaction = Transaction::create([
                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'] ?? null,
                'wa_number' => $validated['wa_number'],
                'note' => $validated['note'] ?? null,
                'order_date' => now(),
                'pick_up_date' => $pickupDateTime,
                'id_location' => $validated['id_location'],
                'total_price' => $total,
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                $item['id_transaction'] = $transaction->id;
                TransactionItem::create($item);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat',
                'transaction_id' => $transaction->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
