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
                    @php
                        $statusColors = [
                            'pending' => 'bg-secondary-100 text-secondary-800',
                            'paid' => 'bg-blue-100 text-blue-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            'wasted' => 'bg-gray-200 text-gray-600',
                        ];
                    @endphp
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        @if($order->status === 'pending')
                            Menunggu Pembayaran
                        @elseif($order->status === 'paid')
                            Sudah Dibayar
                        @elseif($order->status === 'completed')
                            Diambil
                        @elseif($order->status === 'cancelled')
                            Dibatalkan
                        @elseif($order->status === 'wasted')
                            Tidak Diambil
                        @else
                            {{ ucfirst($order->status) }}
                        @endif
                    </span>
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
            <a href="{{ url()->previous() }}" class="inline-block px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium">Kembali</a>
        </div>
    </div>
</div>
@endsection
