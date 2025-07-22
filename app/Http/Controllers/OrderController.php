<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Payment;
use App\Models\Menu;
use App\Models\Location;
use App\Models\PickupTime;
use App\Services\QrCodeService;
use App\Services\MidtransService;

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
            'wa_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,9}$/'
            ],
            'customer_email' => 'nullable|email|max:100',
            'note' => 'nullable|string',
            'pick_up_date' => 'required|date',
            'pickup_time' => 'required|string', // Format HH:MM
            'location_id' => 'required|exists:locations,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.qty' => 'required|integer|min:1',
        ], [
            'wa_number.required' => 'Nomor WhatsApp wajib diisi.',
            'wa_number.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format: 08xxxxxxx atau +62xxxxxxx',
            'customer_email.email' => 'Format email tidak valid.',
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'location_id.required' => 'Lokasi pickup wajib dipilih.',
            'pickup_time.required' => 'Jam pickup wajib dipilih.',
            'items.required' => 'Minimal satu item harus dipilih.',
            'items.min' => 'Minimal satu item harus dipilih.',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $items = [];

            // Validate stock availability first
            foreach ($validated['items'] as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                
                // Check if item is still active
                if (!$menu->is_active) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Menu '{$menu->name}' tidak tersedia."
                    ], 400);
                }
                
                // Check stock availability
                if ($menu->stock < $item['qty']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Stok menu '{$menu->name}' tidak mencukupi. Tersedia: {$menu->stock}, diminta: {$item['qty']}"
                    ], 400);
                }
                
                $subtotal = $menu->price * $item['qty'];
                $total += $subtotal;

                $items[] = [
                    'menu_id' => $menu->getKey(),
                    'qty' => $item['qty'],
                    'price_per_item' => $menu->price,
                    'total_price' => $subtotal,
                ];
            }

            // Gabungkan tanggal dan waktu pickup
            $pickupDateTime = $validated['pick_up_date'] . ' ' . $validated['pickup_time'] . ':00';

            // Create transaction
            $transaction = Transaction::create([
                'customer_name' => $validated['customer_name'],
                'wa_number' => $validated['wa_number'],
                'customer_email' => $validated['customer_email'] ?? null,
                'note' => $validated['note'] ?? null,
                'order_date' => now()->setTimezone('Asia/Jakarta'),
                'pick_up_date' => $pickupDateTime,
                'location_id' => $validated['location_id'],
                'total_price' => $total,
                'status' => 'pending',
            ]);

            // Create transaction items
            foreach ($items as $item) {
                $item['transaction_id'] = $transaction->getKey();
                TransactionItem::create($item);
            }

            // Reload transaction with items for Midtrans
            $transaction->load('items.menu.category', 'location');

            // Create Midtrans payment
            $midtransService = new MidtransService();
            $paymentResult = $midtransService->createTransaction($transaction);

            if (!$paymentResult['success']) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat pembayaran: ' . $paymentResult['message']
                ], 500);
            }

            // Create payment record
            $payment = Payment::create([
                'transaction_id' => $transaction->getKey(),
                'payment_gateway' => 'midtrans',
                'gateway_order_id' => 'ORDER-' . $transaction->getKey() . '-' . time(),
                'snap_token' => $paymentResult['snap_token'],
                'status' => 'pending',
                'amount' => $total,
                'expired_at' => now()->addHours(24), // 24 hours expiry
            ]);

            DB::commit();

            // Generate QR Code untuk pickup menggunakan pickup code
            $qrCodeDataUri = QrCodeService::generatePickupQrDataUri($transaction->getAttribute('pickup_code'));

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat',
                'transaction_id' => $transaction->getKey(),
                'payment_id' => $payment->getKey(),
                'pickup_code' => $transaction->getAttribute('pickup_code'),
                'qr_code' => $qrCodeDataUri,
                'snap_token' => $paymentResult['snap_token'],
                'client_key' => config('services.midtrans.client_key')
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
