@extends('admin.layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Detail Pesanan</h2>
            <span class="text-sm text-gray-500">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <div class="mb-2">
                    <span class="font-medium text-gray-700">Nama Customer:</span>
                    <span class="text-gray-900">{{ $order->customer_name }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-medium text-gray-700">WhatsApp:</span>
                    <span class="text-gray-900">{{ $order->wa_number }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-medium text-gray-700">Lokasi Pickup:</span>
                    <span class="text-gray-900">{{ $order->location->name ?? 'N/A' }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-medium text-gray-700">Jam Pickup:</span>
                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($order->pick_up_date)->format('H:i') }}</span>
                </div>
            </div>
            <div>
                <div class="mb-2">
                    <span class="font-medium text-gray-700">Total:</span>
                    <span class="text-gray-900">Rp {{ number_format($order->total_price) }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-medium text-gray-700">Status:</span>
                    <!-- Status Update Form -->
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border-gray-300 rounded-md text-xs py-1 px-2 mr-2">
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Menunggu Pickup</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Sudah Diambil</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="wasted" {{ $order->status == 'wasted' ? 'selected' : '' }}>Tidak Diambil</option>
                        </select>
                        <button type="submit" class="bg-primary-500 text-white text-xs px-3 py-1 rounded">Ubah</button>
                    </form>
                </div>
                @if($order->notes)
                <div class="mb-2">
                    <span class="font-medium text-gray-700">Catatan:</span>
                    <span class="text-gray-900">{{ $order->notes }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items Table (if available) -->

        @if($order->relationLoaded('items') && $order->items->count())
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Daftar Item Pesanan</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Menu</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga per Item</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $item->menu->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">Rp {{ number_format($item->price_per_item) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $item->qty }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">Rp {{ number_format($item->total_price) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right font-semibold text-gray-900">Total</td>
                            <td class="px-4 py-2 text-sm font-bold text-gray-900">Rp {{ number_format($order->total_price) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="flex justify-end">
            <a href="{{ route('admin.dashboard') }}" class="inline-block px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium">Kembali</a>
        </div>
    </div>
</div>
@endsection
