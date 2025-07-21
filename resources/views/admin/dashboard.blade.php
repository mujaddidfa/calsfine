<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - CalsFine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
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
                <h2 class="text-3xl font-bold text-gray-900">Dashboard CalsFine</h2>
                <button type="button" onclick="openQrScanner(); console.log('Button clicked');" class="inline-flex items-center px-5 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-semibold rounded-lg shadow transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="14" y="3" width="7" height="7" rx="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="14" y="14" width="7" height="7" rx="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="3" y="14" width="7" height="7" rx="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Scan QR Pickup
                </button>
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
                        <h3 class="text-sm font-medium text-gray-500">Pickup Hari Ini</h3>
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
                            Pickup Hari Ini ({{ count($recent_orders) }})
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
                            <h3 class="text-lg font-semibold text-gray-900">Pickup Hari Ini</h3>
                            <p class="text-gray-600 text-sm">Pesanan yang dijadwalkan pickup hari ini - {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
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
                                            Dibatalkan
                                        </span>
                                    @elseif($order->status === 'wasted')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Tidak Diambil
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary-600 hover:text-primary-800 font-medium">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center">
                                    <div class="text-gray-400">
                                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <p class="text-gray-500 font-medium">Belum ada pesanan pickup hari ini</p>
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
                            <p class="text-gray-600 text-sm">Pesanan yang dipesan hari ini untuk pickup besok - {{ now()->addDay()->locale('id')->translatedFormat('l, j F Y') }}</p>
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
                                    @elseif($order->status === 'wasted')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Tidak Diambil
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary-600 hover:text-primary-800 font-medium">Detail</a>
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

    <!-- QR Scanner Modal -->
    <div id="qr-scanner-modal" class="fixed inset-0 bg-neutral-900/25 hidden items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl p-4 max-w-md w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">
                    🔍 Scan QR Code
                </h3>
                <button onclick="closeQrScanner()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">
                    ×
                </button>
            </div>

            <!-- Toggle Buttons -->
            <div class="flex bg-gray-100 rounded-lg p-1 mb-4">
                <button onclick="showCameraScanner()" id="camera-toggle" 
                        class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 bg-blue-500 text-white">
                    📷 Kamera
                </button>
                <button onclick="showManualScanner()" id="manual-toggle" 
                        class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 text-gray-700 hover:text-gray-900">
                    ⌨️ Manual
                </button>
            </div>

            <!-- Camera Scanner -->
            <div id="camera-scanner" class="scanner-mode">
                <div id="qr-reader" class="w-full mb-3" style="max-width: 100%; height: 250px;"></div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                    <p class="text-xs text-blue-700">
                        <strong>Petunjuk:</strong> Arahkan kamera ke QR Code
                    </p>
                </div>
            </div>

            <!-- Manual Input -->
            <div id="manual-scanner" class="scanner-mode hidden">
                <div class="mb-3">
                    <label for="qr-code-input" class="block text-sm font-medium text-gray-700 mb-2">
                        Pickup Code:
                    </label>
                    <input type="text" id="qr-code-input" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" 
                           placeholder="Contoh: ABC12345"
                           maxlength="8"
                           style="text-transform: uppercase;">
                </div>
                
                <button onclick="processPickup()" 
                        class="w-full bg-green-500 text-white py-2 px-3 rounded-md hover:bg-green-600 transition text-sm font-medium mb-3">
                    ✅ Proses Pickup
                </button>
                
                <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                    <p class="text-xs text-blue-700">
                        <strong>Cara:</strong> Ketik pickup code lalu klik "Proses Pickup"
                    </p>
                </div>
            </div>

            <!-- Close Button -->
            <div class="mt-4 pt-3 border-t border-gray-200">
                <button onclick="closeQrScanner()" 
                        class="w-full bg-gray-500 text-white py-2 px-3 rounded-md hover:bg-gray-600 transition text-sm font-medium">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        // QR Scanner variables
        let html5QrCode = null;
        let currentScannerMode = 'camera';
        
        // Tab switching function
        function switchTab(tab) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-primary-500', 'text-primary-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            // Show selected tab content
            document.getElementById(tab + '-content').classList.remove('hidden');
            
            // Add active class to selected tab button
            const activeButton = document.getElementById('tab-' + tab);
            activeButton.classList.add('active', 'border-primary-500', 'text-primary-600');
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:border-gray-300');
        }
        
        // Scanner mode switching
        function showCameraScanner() {
            currentScannerMode = 'camera';
            
            // Update button appearance
            const cameraToggle = document.getElementById('camera-toggle');
            const manualToggle = document.getElementById('manual-toggle');
            
            cameraToggle.classList.add('bg-blue-500', 'text-white');
            cameraToggle.classList.remove('text-gray-700');
            manualToggle.classList.remove('bg-blue-500', 'text-white');
            manualToggle.classList.add('text-gray-700');
            
            // Show camera scanner, hide manual
            document.getElementById('camera-scanner').classList.remove('hidden');
            document.getElementById('manual-scanner').classList.add('hidden');
            
            // Auto-start camera
            setTimeout(() => {
                startQrScanner();
            }, 100);
        }
        
        function showManualScanner() {
            currentScannerMode = 'manual';
            
            // Update button appearance
            const cameraToggle = document.getElementById('camera-toggle');
            const manualToggle = document.getElementById('manual-toggle');
            
            manualToggle.classList.add('bg-blue-500', 'text-white');
            manualToggle.classList.remove('text-gray-700');
            cameraToggle.classList.remove('bg-blue-500', 'text-white');
            cameraToggle.classList.add('text-gray-700');
            
            // Show manual scanner, hide camera
            document.getElementById('manual-scanner').classList.remove('hidden');
            document.getElementById('camera-scanner').classList.add('hidden');
            
            // Stop camera if running
            try {
                if (html5QrCode && html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
                    stopQrScanner();
                }
            } catch (err) {
                console.log('Error checking scanner state:', err);
            }
        }
        
        // QR Scanner functions
        function openQrScanner() {
            console.log('openQrScanner() called'); // Debug log
            const modal = document.getElementById('qr-scanner-modal');
            console.log('Modal element:', modal); // Debug log
            
            if (!modal) {
                alert('Error: Modal element not found!');
                return;
            }
            
            // Use both methods to ensure modal shows
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Reset to camera mode by default
            showCameraScanner();
        }
        
        function closeQrScanner() {
            // Stop camera if running
            try {
                if (html5QrCode && html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
                    stopQrScanner();
                }
            } catch (err) {
                console.log('Error checking scanner state:', err);
            }
            
            const modal = document.getElementById('qr-scanner-modal');
            // Use both methods to ensure modal hides
            modal.style.display = 'none';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            // Clear manual input
            const input = document.getElementById('qr-code-input');
            if (input) {
                input.value = '';
            }
        }
        
        // Start QR Code scanner with camera
        async function startQrScanner() {
            try {
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("qr-reader");
                }
                
                // Get available cameras
                const devices = await Html5Qrcode.getCameras();
                if (devices && devices.length) {
                    // Use back camera if available, otherwise use first camera
                    const cameraId = devices.find(device => 
                        device.label.toLowerCase().includes('back') || 
                        device.label.toLowerCase().includes('rear')
                    )?.id || devices[0].id;
                    
                    // Start scanning
                    await html5QrCode.start(
                        cameraId,
                        {
                            fps: 10,
                            qrbox: { width: 200, height: 200 }
                        },
                        onScanSuccess,
                        onScanFailure
                    );
                    
                } else {
                    alert('❌ Tidak ditemukan kamera pada perangkat ini. Gunakan mode manual.');
                    showManualScanner();
                }
            } catch (err) {
                console.error('Error starting QR scanner:', err);
                alert('❌ Gagal mengakses kamera. Pastikan browser memiliki izin kamera atau gunakan mode manual.');
                showManualScanner();
            }
        }
        
        // Stop QR Code scanner
        function stopQrScanner() {
            if (html5QrCode && html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
                html5QrCode.stop().then(() => {
                    console.log('QR Code scanner stopped');
                }).catch(err => {
                    console.error('Error stopping scanner:', err);
                });
            }
        }
        
        // Handle successful QR code scan
        function onScanSuccess(decodedText, decodedResult) {
            console.log('QR Code scanned:', decodedText);
            
            // Stop scanning
            stopQrScanner();
            
            // Process the scanned QR code
            processScannedQrCode(decodedText);
        }
        
        // Handle scan failure (optional)
        function onScanFailure(error) {
            // Silently handle scan failures - they happen frequently as the scanner tries to read
            // console.warn('QR scan failed:', error);
        }
        
        // Process scanned QR code or manual input
        function processScannedQrCode(qrCodeData) {
            // Extract pickup code from URL if it's a QR code URL
            let pickupCode = qrCodeData.toUpperCase();
            if (qrCodeData.includes('/admin/pickup/scan/')) {
                const urlParts = qrCodeData.split('/admin/pickup/scan/');
                pickupCode = urlParts[1];
            }
            
            // Show confirmation before processing
            const confirmMessage = `Akan memproses pickup untuk code: ${pickupCode}\n\nLanjutkan?`;
            if (confirm(confirmMessage)) {
                processPickupByCode(pickupCode);
            } else {
                // If cancelled, restart scanner
                if (currentScannerMode === 'camera') {
                    startQrScanner();
                }
            }
        }
        
        // Process pickup using manual input
        async function processPickup() {
            const input = document.getElementById('qr-code-input');
            const value = input.value.trim();
            
            if (!value) {
                alert('Harap masukkan Pickup Code atau URL QR Code!');
                input.focus();
                return;
            }
            
            processScannedQrCode(value);
        }
        
        // Process pickup by pickup code
        async function processPickupByCode(pickupCode) {
            try {
                const response = await fetch(`/admin/api/pickup/scan/${pickupCode}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(`✅ Pickup berhasil!\n\nCustomer: ${result.data.customer_name}\nTotal: Rp ${result.data.total_price.toLocaleString('id-ID')}\nPesanan ID: #${result.data.order_id}\nPickup Code: ${result.data.pickup_code}`);
                    
                    // Close modal and refresh page
                    closeQrScanner();
                    location.reload();
                } else {
                    alert(`❌ ${result.message}`);
                    
                    // Restart scanner if in camera mode
                    if (currentScannerMode === 'camera') {
                        setTimeout(() => startQrScanner(), 1000);
                    }
                }
            } catch (error) {
                console.error('Pickup error:', error);
                alert('❌ Terjadi kesalahan saat memproses pickup. Silakan coba lagi.');
                
                // Restart scanner if in camera mode
                if (currentScannerMode === 'camera') {
                    setTimeout(() => startQrScanner(), 1000);
                }
            }
        }
        
        // Allow Enter key to process pickup in manual mode
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded'); // Debug log
            
            // Check if modal exists
            const modal = document.getElementById('qr-scanner-modal');
            console.log('Modal on load:', modal); // Debug log
            
            const qrInput = document.getElementById('qr-code-input');
            if (qrInput) {
                qrInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        processPickup();
                    }
                });
            }
            
            // Test function to ensure modal works
            window.testModal = function() {
                const modal = document.getElementById('qr-scanner-modal');
                if (modal) {
                    modal.style.display = 'flex';
                    console.log('Test modal opened');
                } else {
                    console.log('Modal not found in test');
                }
            };
        });
    </script>
</body>
</html>
