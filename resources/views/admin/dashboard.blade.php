<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CalsFine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Page Header with H-1 Info -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Dashboard CalsFine</h2>
                </div>
            </div>
        </div>

        <!-- Stats Cards for H-1 Order System -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Penjualan Hari Ini -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-primary-100 text-primary-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Penjualan Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['today_revenue']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['today_orders'] }} pesanan</p>
                    </div>
                </div>
            </div>

            <!-- Pesanan Diambil -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Pesanan Diambil</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_orders'] }}</p>
                        <p class="text-xs text-green-600 mt-1">{{ $stats['pickup_rate'] }}% pickup rate</p>
                    </div>
                </div>
            </div>

            <!-- Pesanan Belum Diambil -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Pesanan Belum Diambil</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</p>
                        <p class="text-xs text-red-600 mt-1">Belum pickup</p>
                    </div>
                </div>
            </div>

            <!-- Order H-1 untuk Besok -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-secondary-100 text-secondary-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Pesanan Besok</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['tomorrow_orders'] }}</p>
                        <p class="text-xs text-secondary-600 mt-1">Siap untuk besok</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions for H-1 Order Management -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- History dan Analytics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">History & Analytics</h3>
                        <p class="text-gray-600 text-sm mb-4">Lihat riwayat penjualan dan analisis data</p>
                        <a href="{{ route('admin.analytics') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 inline-block">
                            Lihat Analytics
                        </a>
                    </div>
                    <div class="text-primary-500">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Kelola Menu -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola Menu</h3>
                        <p class="text-gray-600 text-sm mb-4">Tambah, edit, dan hapus menu makanan</p>
                        <a href="{{ route('admin.menus') }}" class="bg-secondary-500 hover:bg-secondary-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 inline-block">
                            Kelola Menu
                        </a>
                    </div>
                    <div class="text-secondary-500">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Kelola Kategori -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola Kategori</h3>
                        <p class="text-gray-600 text-sm mb-4">Atur kategori untuk menu makanan</p>
                        <a href="{{ route('admin.categories') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 inline-block">
                            Kelola Kategori
                        </a>
                    </div>
                    <div class="text-blue-500">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Kelola Lokasi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola Lokasi</h3>
                        <p class="text-gray-600 text-sm mb-4">Atur lokasi pickup untuk customer</p>
                        <a href="{{ route('admin.locations') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 inline-block">
                            Kelola Lokasi
                        </a>
                    </div>
                    <div class="text-green-500">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Management with Tabs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="switchTab('today')" id="tab-today" class="tab-button active border-b-2 border-primary-500 text-primary-600 py-4 px-1 text-sm font-medium">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pesanan Hari Ini ({{ count($recent_orders) }})
                        </span>
                    </button>
                    <button onclick="switchTab('tomorrow')" id="tab-tomorrow" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                            </svg>
                            Pesanan Besok ({{ count($tomorrow_orders ?? []) }})
                        </span>
                    </button>
                </nav>
            </div>

            <!-- Today Orders Tab Content -->
            <div id="today-content" class="tab-content">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pesanan Hari Ini</h3>
                            <p class="text-gray-600 text-sm">Status pengambilan pesanan - {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">{{ count($recent_orders) }}</span> pesanan
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recent_orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->customer_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->wa_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->location->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">Rp {{ number_format($order->total_price) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->pick_up_date)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->status === 'pending')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-secondary-100 text-secondary-800">
                                            Menunggu Pembayaran
                                        </span>
                                    @elseif($order->status === 'paid')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Siap Pickup
                                        </span>
                                    @elseif($order->status === 'completed')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Sudah Diambil
                                        </span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Tidak Diambil
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="text-primary-600 hover:text-primary-800 font-medium">Detail</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center">
                                    <div class="text-gray-400">
                                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <p class="text-gray-500 font-medium">Belum ada pesanan hari ini</p>
                                        <p class="text-gray-400 text-sm mt-1">Pesanan H-1 akan muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tomorrow Orders Tab Content -->
            <div id="tomorrow-content" class="tab-content hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pesanan Besok</h3>
                            <p class="text-gray-600 text-sm">Daftar pesanan yang akan diambil besok - {{ now()->addDay()->locale('id')->translatedFormat('l, j F Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">{{ count($tomorrow_orders ?? []) }}</span> pesanan
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="text-gray-500">Revenue:</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($tomorrow_orders->sum('total_price') ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tomorrow_orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->customer_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->wa_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->location->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">Rp {{ number_format($order->total_price) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->pick_up_date)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->status === 'pending')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Menunggu Pembayaran
                                        </span>
                                    @elseif($order->status === 'paid')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Siap Pickup
                                        </span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="text-primary-600 hover:text-primary-800 font-medium">Detail</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center">
                                    <div class="text-gray-400">
                                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 font-medium">Belum ada pesanan untuk besok</p>
                                        <p class="text-gray-400 text-sm mt-1">Pesanan H-1 akan muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching function
        function switchTab(tab) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-primary-500', 'text-primary-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tab + '-content').classList.remove('hidden');
            
            // Add active class to selected tab button
            const activeButton = document.getElementById('tab-' + tab);
            activeButton.classList.add('active', 'border-primary-500', 'text-primary-600');
            activeButton.classList.remove('border-transparent', 'text-gray-500');
        }
    </script>
</body>
</html>
