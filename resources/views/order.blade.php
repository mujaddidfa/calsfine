<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calsfine - Makan Sehat, Praktis, dan Lezat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Midtrans Snap -->
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Order Page Header -->
    <header class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Back Button and Logo -->
                <div class="flex items-center">
                    <!-- Back Button -->
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-50 mr-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Kembali
                        </span>
                    </a>
                </div>
                
                <!-- Logo Center -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="hover:opacity-80 transition">
                        <img src="{{ asset('images/logo.png') }}" alt="CalsFine Logo" class="h-8 w-auto">
                    </a>
                </div>
            </div>
        </div>
    </header>
    <section class="bg-neutral-50 py-12 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">

            <!-- Logo + Tagline -->
            <div class="text-center my-8">
                <img src="{{ asset('images/logo.png') }}" alt="CalsFine Logo" class="h-24 md:h-32 mx-auto">
            </div>

            <!-- Search Bar -->   
            <div class="max-w-2xl mx-auto mb-10">   
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search" id="default-search" class="block w-full p-4 ps-10 pr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="Cari menu favorit Anda..." oninput="searchProducts(event)" />
                </div>
            </div>

            <!-- Grid Produk -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6" id="products-grid">
            
            @forelse($menus as $menu)
            <!-- Card Produk -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($menu->image && $menu->image !== 'default.jpg' && file_exists(public_path('storage/' . $menu->image)))
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-sm">Foto Produk</span>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold mb-1">{{ $menu->name }}</h3>
                    @if($menu->category)
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded mb-2 inline-block">{{ $menu->category->name }}</span>
                    @endif
                    <p class="text-xs text-gray-600 mb-2">{{ $menu->description }}</p>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm font-semibold text-primary-500">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <span class="text-xs text-gray-500">Stok: {{ $menu->stock }}</span>
                    </div>
                    <button 
                        onclick="addToCart({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }}, {{ $menu->stock }})"
                        class="w-full text-sm border border-primary-500 text-primary-500 py-1 rounded hover:bg-primary-500 hover:text-white transition">
                        Tambah
                    </button>
                </div>
            </div>
            @empty
            <!-- Fallback jika tidak ada data -->
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">Belum ada produk tersedia</p>
            </div>
            @endforelse

            </div>
        </div>
    </section>

    <!-- Keranjang Fixed Right Side -->
    <div id="cart-sidebar" class="fixed top-0 right-0 h-full w-80 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-40">
        <!-- Cart Title -->
        <div class="bg-primary-500 text-white p-4">
            <h2 class="text-xl font-bold text-center">Keranjang Belanja</h2>
        </div>
        
        <!-- Cart Header -->
        <div class="bg-white border-b border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold flex items-center">
                    Keranjang
                    <span id="cart-count" class="ml-2 bg-primary-500 text-white text-xs px-2 py-1 rounded-full">0</span>
                </h3>
                <button onclick="toggleCart()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Cart Content -->
        <div class="flex flex-col" style="height: calc(100vh - 140px);">
            <!-- Cart Items -->
            <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3">
                <!-- Items akan diisi oleh JavaScript -->
            </div>

            <!-- Cart Summary -->
            <div id="cart-summary" class="border-t bg-gray-50 p-4">
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Item:</span>
                        <span id="total-items" class="text-sm font-medium">0</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-t border-gray-200">
                        <span class="text-base font-semibold text-gray-800">Total Harga:</span>
                        <span id="total-price" class="text-lg font-bold text-primary-600">Rp <span id="checkout-total">0</span></span>
                    </div>
                </div>
                <button 
                    onclick="checkout()"
                    id="checkout-btn"
                    class="w-full bg-primary-500 text-white py-3 px-4 rounded-lg hover:bg-primary-600 transition font-semibold text-base shadow-md hover:shadow-lg relative flex items-center">
                    <img src="{{ asset('images/shopping-basket.svg') }}" alt="Checkout" class="w-5 h-5 filter invert absolute left-4">
                    <span class="flex-1 text-center">Checkout</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Toggle Button (Fixed) -->
    <button id="cart-toggle" class="fixed top-1/2 right-4 transform -translate-y-1/2 bg-primary-500 text-white p-3 rounded-full shadow-lg hover:bg-primary-600 transition z-30">
        <img src="{{ asset('images/shopping-basket.svg') }}" alt="Cart" class="w-6 h-6 filter invert">
        <span id="cart-toggle-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[20px] text-center">0</span>
    </button>

    <!-- Overlay -->
    <div id="cart-overlay" class="fixed inset-0 bg-neutral-900/25 z-30 hidden" onclick="toggleCart()"></div>

    <!-- Checkout Modal -->
    <div id="checkout-modal" class="fixed inset-0 bg-neutral-900/25 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-primary-500 text-white p-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Checkout</h2>
                    <button onclick="closeCheckoutModal()" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <!-- Order Summary -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Ringkasan Pesanan</h3>
                    <div id="checkout-items" class="space-y-2 mb-4">
                        <!-- Items akan diisi oleh JavaScript -->
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total</span>
                            <span class="text-primary-600">Rp <span id="checkout-modal-total">0</span></span>
                        </div>
                    </div>
                </div>

                <!-- Customer Form -->
                <form id="checkout-form" class="space-y-4">
                    <div>
                        <label for="customer-name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="customer-name" 
                            name="customer_name"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Masukkan nama lengkap Anda"
                            minlength="2"
                            maxlength="100"
                            required>
                        <div class="text-gray-500 text-xs mt-1">Nama yang akan dipanggil saat pickup</div>
                    </div>

                    <div>
                        <label for="customer-phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="tel" 
                            id="customer-phone" 
                            name="wa_number"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="08xxxxxxxxxx atau +62xxxxxxxxx"
                            maxlength="20"
                            oninput="validateWhatsAppNumber(this)"
                            required>
                        <div id="phone-error" class="text-red-500 text-xs mt-1 hidden">Format nomor WhatsApp tidak valid. Gunakan format: 08xxxxxxx atau +62xxxxxxx</div>
                        <div class="text-gray-500 text-xs mt-1">Untuk konfirmasi pesanan dan pickup</div>
                    </div>

                    <div>
                        <label for="customer-email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email (Opsional)
                        </label>
                        <input 
                            type="email" 
                            id="customer-email" 
                            name="customer_email"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="email@example.com"
                            maxlength="100"
                            oninput="validateEmail(this)">
                        <div id="email-error" class="text-red-500 text-xs mt-1 hidden">Format email tidak valid</div>
                        <div class="text-gray-500 text-xs mt-1">Untuk menerima notifikasi tambahan</div>
                    </div>

                    <div>
                        <label for="pickup-location" class="block text-sm font-medium text-gray-700 mb-1">
                            Lokasi Pickup <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="pickup-location" 
                            name="location_id"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            required>
                            <option value="">Pilih lokasi pickup...</option>
                            <!-- Options akan diisi oleh JavaScript atau dari backend -->
                        </select>
                    </div>

                    <div>
                        <label for="pickup-time" class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Pickup <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="pickup-time" 
                            name="pickup_time"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            required>
                            <option value="">Pilih jam pickup...</option>
                            <!-- Options akan diisi oleh JavaScript -->
                        </select>
                    </div>

                    <!-- Hidden pickup date field (automatically set to tomorrow) -->
                    <input type="hidden" id="pickup-date" name="pick_up_date">

                    <!-- Pickup date info for user -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Tanggal Pickup</p>
                                <p class="text-sm text-blue-600" id="pickup-date-display">Besok</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="order-notes" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan Pesanan
                        </label>
                        <textarea 
                            id="order-notes" 
                            name="note"
                            rows="3"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Catatan khusus untuk pesanan Anda (opsional)"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3 pt-4">
                        <button 
                            type="button" 
                            onclick="closeCheckoutModal()"
                            class="flex-1 bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition font-medium">
                            Batal
                        </button>
                        <button 
                            type="button"
                            onclick="showOrderPreview()"
                            class="flex-1 bg-primary-500 text-white py-3 px-4 rounded-lg hover:bg-primary-600 transition font-medium">
                            Pratinjau Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Preview Modal -->
    <div id="order-preview-modal" class="fixed inset-0 bg-neutral-900/25 z-60 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-primary-500 text-white p-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Pratinjau Pesanan</h2>
                    <button onclick="closeOrderPreview()" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <!-- Customer Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">Informasi Pelanggan</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Nama:</span>
                            <span class="text-sm font-medium" id="preview-customer-name">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">WhatsApp:</span>
                            <span class="text-sm font-medium" id="preview-customer-phone">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Lokasi Pickup:</span>
                            <span class="text-sm font-medium" id="preview-pickup-location">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Jam Pickup:</span>
                            <span class="text-sm font-medium" id="preview-pickup-time">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tanggal Pickup:</span>
                            <span class="text-sm font-medium" id="preview-pickup-date">-</span>
                        </div>
                        <div id="preview-notes-container" class="hidden">
                            <div class="border-t pt-2 mt-2">
                                <span class="text-sm text-gray-600">Catatan:</span>
                                <p class="text-sm font-medium mt-1" id="preview-notes">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">Detail Pesanan</h3>
                    <div id="preview-items" class="space-y-3 mb-4">
                        <!-- Items akan diisi oleh JavaScript -->
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="border-t pt-4">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Item:</span>
                                <span class="font-medium" id="preview-total-items">0</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t pt-2">
                                <span class="text-gray-800">Total Harga:</span>
                                <span class="text-primary-600">Rp <span id="preview-total-price">0</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Informasi Pembayaran</p>
                                <p class="text-sm text-blue-700 mt-1">
                                    Setelah konfirmasi pesanan, Anda akan diarahkan ke halaman pembayaran yang aman. 
                                    Tersedia berbagai metode pembayaran: Transfer Bank, E-wallet (OVO, DANA, LinkAja), 
                                    Kartu Kredit/Debit, dan lainnya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button 
                        type="button" 
                        onclick="backToCheckoutForm()"
                        class="flex-1 bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </button>
                    <button 
                        type="button"
                        onclick="confirmOrder()"
                        class="flex-1 bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Konfirmasi Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data locations dan pickup times dari backend
        const locations = @json($locations ?? []);
        const pickupTimes = @json($pickupTimes ?? []);
        
        // Cart functionality
        let cart = [];
        let isCartOpen = false;

        // Validation functions
        function validateWhatsAppNumber(input) {
            const phoneError = document.getElementById('phone-error');
            let phoneValue = input.value.trim();
            
            // Auto-format: remove all non-digits first
            let cleanPhone = phoneValue.replace(/[^\d]/g, '');
            
            // Auto-format: if starts with 62, add +
            if (cleanPhone.startsWith('62')) {
                cleanPhone = '+' + cleanPhone;
            }
            // Auto-format: if starts with 8, add 0
            else if (cleanPhone.startsWith('8') && cleanPhone.length > 1) {
                cleanPhone = '0' + cleanPhone;
            }
            
            // Update input value with formatted number
            if (cleanPhone !== phoneValue) {
                input.value = cleanPhone;
                phoneValue = cleanPhone;
            }
            
            // Indonesian phone number patterns
            const indonesianPhoneRegex = /^(\+62|62|0)8[1-9][0-9]{6,9}$/;
            
            if (phoneValue === '') {
                // Empty field - hide error for required validation to handle
                phoneError.classList.add('hidden');
                input.classList.remove('border-red-500');
                return true;
            }
            
            if (!indonesianPhoneRegex.test(phoneValue)) {
                phoneError.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            } else {
                phoneError.classList.add('hidden');
                input.classList.remove('border-red-500');
                return true;
            }
        }

        function validateEmail(input) {
            const emailError = document.getElementById('email-error');
            const emailValue = input.value.trim();
            
            if (emailValue === '') {
                // Empty field is allowed for optional email
                emailError.classList.add('hidden');
                input.classList.remove('border-red-500');
                return true;
            }
            
            // Simple but robust email regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(emailValue)) {
                emailError.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            } else {
                emailError.classList.add('hidden');
                input.classList.remove('border-red-500');
                return true;
            }
        }

        function validateForm() {
            const phoneInput = document.getElementById('customer-phone');
            const emailInput = document.getElementById('customer-email');
            
            const isPhoneValid = validateWhatsAppNumber(phoneInput);
            const isEmailValid = validateEmail(emailInput);
            
            return isPhoneValid && isEmailValid;
        }

        // Populate location options
        document.addEventListener('DOMContentLoaded', function() {
            const locationSelect = document.getElementById('pickup-location');
            const timeSelect = document.getElementById('pickup-time');

            // Populate locations
            if (locationSelect && locations && locations.length > 0) {
                locations.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location.id;
                    option.textContent = location.name;
                    locationSelect.appendChild(option);
                });
            }

            // Handle location change to filter pickup times
            if (locationSelect && timeSelect) {
                locationSelect.addEventListener('change', function() {
                    const selectedLocationId = parseInt(this.value);
                    
                    // Clear existing time options
                    timeSelect.innerHTML = '<option value="">Pilih jam pickup...</option>';
                    
                    if (selectedLocationId && pickupTimes && pickupTimes.length > 0) {
                        // Filter pickup times for selected location
                        const filteredTimes = pickupTimes.filter(time => 
                            parseInt(time.location_id) === selectedLocationId
                        );
                        
                        // Populate time options
                        filteredTimes.forEach(time => {
                            const option = document.createElement('option');
                            option.value = time.pickup_time.substring(0, 5); // Format HH:MM sebagai value
                            option.textContent = time.pickup_time.substring(0, 5); // Format HH:MM
                            timeSelect.appendChild(option);
                        });
                    }
                });
            }

            // Set pickup date to tomorrow
            const pickupDateInput = document.getElementById('pickup-date');
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const formattedDate = tomorrow.toISOString().split('T')[0];
            
            if (pickupDateInput) {
                pickupDateInput.value = formattedDate;
            }

            // Update pickup date display
            const pickupDateDisplay = document.getElementById('pickup-date-display');
            if (pickupDateDisplay) {
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                pickupDateDisplay.textContent = tomorrow.toLocaleDateString('id-ID', options);
            }

            // Initialize cart display
            updateCartDisplay();

            // Event listener for cart toggle button
            document.getElementById("cart-toggle").addEventListener("click", toggleCart);
        });

        // Cart Management Functions
        window.addToCart = function(id, name, price, stock) {
            const existingItem = cart.find((item) => item.id === id);

            if (existingItem) {
                if (existingItem.quantity < stock) {
                    existingItem.quantity++;
                } else {
                    alert('Stok tidak mencukupi!');
                    return;
                }
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    quantity: 1,
                    stock: stock,
                });
            }

            updateCartDisplay();
            showCartElements();

            // Auto-open cart when first item is added
            if (cart.length === 1 && !isCartOpen) {
                toggleCart();
            }
        }

        window.updateQuantity = function(id, change) {
            const item = cart.find((item) => item.id === id);
            if (!item) return;

            const newQuantity = item.quantity + change;

            if (newQuantity <= 0) {
                removeFromCart(id);
            } else if (newQuantity <= item.stock) {
                item.quantity = newQuantity;
                updateCartDisplay();
            } else {
                alert('Stok tidak mencukupi!');
            }
        }

        window.removeFromCart = function(id) {
            cart = cart.filter((item) => item.id !== id);
            updateCartDisplay();

            // Close cart if empty and open
            if (cart.length === 0 && isCartOpen) {
                toggleCart();
            }
        }

        window.toggleCart = function() {
            const cartSidebar = document.getElementById("cart-sidebar");
            const cartOverlay = document.getElementById("cart-overlay");

            // Pastikan sidebar terlihat terlebih dahulu
            cartSidebar.classList.remove("hidden");

            if (isCartOpen) {
                cartSidebar.classList.add("translate-x-full");
                cartOverlay.classList.add("hidden");
                isCartOpen = false;
            } else {
                cartSidebar.classList.remove("translate-x-full");
                cartOverlay.classList.remove("hidden");
                isCartOpen = true;
            }
        }

        function showCartElements() {
            const cartSidebar = document.getElementById("cart-sidebar");
            cartSidebar.classList.remove("hidden");
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById("cart-items");
            const cartCount = document.getElementById("cart-count");
            const cartToggleCount = document.getElementById("cart-toggle-count");
            const totalItems = document.getElementById("total-items");
            const checkoutTotal = document.getElementById("checkout-total");

            // Update cart counts
            const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
            const total = cart.reduce(
                (sum, item) => sum + item.price * item.quantity,
                0
            );

            cartCount.textContent = totalQty;
            cartToggleCount.textContent = totalQty;
            totalItems.textContent = totalQty;
            checkoutTotal.textContent = total.toLocaleString("id-ID");

            if (cart.length === 0) {
                // Show empty cart message and clear items
                cartItems.innerHTML = `
                    <div id="empty-cart" class="text-center py-8 text-gray-500">
                        <img src="/images/shopping-basket.svg" alt="Empty Cart" class="w-16 h-16 mx-auto mb-4 opacity-30">
                        <p>Keranjang masih kosong</p>
                        <p class="text-sm">Tambahkan menu untuk mulai berbelanja</p>
                    </div>
                `;
                return;
            }

            // Update cart items - replace entire content
            cartItems.innerHTML = cart
                .map(
                    (item) => `
                <div class="flex items-start justify-between p-3 border border-gray-200 rounded">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium truncate">${item.name}</h4>
                        <p class="text-xs text-gray-600">Rp ${item.price.toLocaleString(
                            "id-ID"
                        )}</p>
                    </div>
                    <div class="flex flex-col items-end space-y-2 ml-2">
                        <div class="flex items-center space-x-1">
                            <button 
                                onclick="updateQuantity(${item.id}, -1)"
                                class="w-6 h-6 bg-gray-200 rounded text-xs hover:bg-gray-300 transition flex items-center justify-center">
                                -
                            </button>
                            <span class="text-sm font-medium w-8 text-center">${
                                item.quantity
                            }</span>
                            <button 
                                onclick="updateQuantity(${item.id}, 1)"
                                class="w-6 h-6 bg-primary-500 text-white rounded text-xs hover:bg-primary-600 transition flex items-center justify-center">
                                +
                            </button>
                        </div>
                        <button 
                            onclick="removeFromCart(${item.id})"
                            class="text-red-500 hover:text-red-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `
                )
                .join("");
        }

        window.checkout = function() {
            if (cart.length === 0) {
                alert("Keranjang masih kosong!");
                return;
            }

            // Show checkout modal
            showCheckoutModal();
        }

        window.showCheckoutModal = function() {
            const modal = document.getElementById("checkout-modal");
            const checkoutItems = document.getElementById("checkout-items");
            const checkoutTotal = document.getElementById("checkout-modal-total");

            // Show modal with flex display
            modal.classList.remove("hidden");
            modal.classList.add("flex");

            // Populate order summary
            const total = cart.reduce(
                (sum, item) => sum + item.price * item.quantity,
                0
            );
            checkoutTotal.textContent = total.toLocaleString("id-ID");

            // Populate items
            checkoutItems.innerHTML = cart
                .map(
                    (item) => `
                <div class="flex justify-between items-center text-sm">
                    <span>${item.name} x${item.quantity}</span>
                    <span>Rp ${(item.price * item.quantity).toLocaleString(
                        "id-ID"
                    )}</span>
                </div>
            `
                )
                .join("");
        }

        window.closeCheckoutModal = function() {
            const modal = document.getElementById("checkout-modal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }

        // Search function
        window.searchProducts = function(event) {
            const searchTerm = event.target.value.toLowerCase();
            const productCards = document.querySelectorAll('#products-grid > div');
            
            productCards.forEach(card => {
                const productName = card.querySelector('h3')?.textContent.toLowerCase() || '';
                const productDescription = card.querySelector('p')?.textContent.toLowerCase() || '';
                
                if (productName.includes(searchTerm) || productDescription.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Order Preview Functions
        window.showOrderPreview = function() {
            const form = document.getElementById("checkout-form");

            // Validate form first
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Custom validation for phone and email
            if (!validateForm()) {
                alert('Mohon periksa kembali format nomor WhatsApp dan email yang Anda masukkan.');
                return;
            }

            const formData = new FormData(form);

            // Hide checkout modal
            const checkoutModal = document.getElementById("checkout-modal");
            checkoutModal.classList.add("hidden");
            checkoutModal.classList.remove("flex");

            // Show preview modal
            const previewModal = document.getElementById("order-preview-modal");
            previewModal.classList.remove("hidden");
            previewModal.classList.add("flex");

            // Populate customer information
            document.getElementById("preview-customer-name").textContent =
                formData.get("customer_name");
            document.getElementById("preview-customer-phone").textContent =
                formData.get("wa_number");

            // Get location name from select
            const locationSelect = document.getElementById("pickup-location");
            const selectedLocation =
                locationSelect.options[locationSelect.selectedIndex].text;
            document.getElementById("preview-pickup-location").textContent =
                selectedLocation;

            // Get pickup time
            const timeSelect = document.getElementById("pickup-time");
            const selectedTime = timeSelect ? timeSelect.value : "";
            document.getElementById("preview-pickup-time").textContent =
                selectedTime || "-";

            // Get formatted pickup date
            const pickupDateDisplay = document.getElementById(
                "pickup-date-display"
            ).textContent;
            document.getElementById("preview-pickup-date").textContent =
                pickupDateDisplay;

            // Handle notes
            const notes = formData.get("note");
            const notesContainer = document.getElementById("preview-notes-container");
            const notesElement = document.getElementById("preview-notes");
            if (notes && notes.trim() !== "") {
                notesContainer.classList.remove("hidden");
                notesElement.textContent = notes;
            } else {
                notesContainer.classList.add("hidden");
            }

            // Populate order items
            populatePreviewItems();
        }

        window.closeOrderPreview = function() {
            const modal = document.getElementById("order-preview-modal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }

        window.backToCheckoutForm = function() {
            // Hide preview modal
            const previewModal = document.getElementById("order-preview-modal");
            previewModal.classList.add("hidden");
            previewModal.classList.remove("flex");

            // Show checkout modal
            const checkoutModal = document.getElementById("checkout-modal");
            checkoutModal.classList.remove("hidden");
            checkoutModal.classList.add("flex");
        }

        function populatePreviewItems() {
            const previewItems = document.getElementById("preview-items");
            const previewTotalItems = document.getElementById("preview-total-items");
            const previewTotalPrice = document.getElementById("preview-total-price");

            // Calculate totals
            const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
            const total = cart.reduce(
                (sum, item) => sum + item.price * item.quantity,
                0
            );

            // Update totals
            previewTotalItems.textContent = totalQty;
            previewTotalPrice.textContent = total.toLocaleString("id-ID");

            // Populate items
            previewItems.innerHTML = cart
                .map(
                    (item) => `
                <div class="bg-white border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${item.name}</h4>
                            <div class="flex items-center mt-1 text-sm text-gray-600">
                                <span>Rp ${item.price.toLocaleString("id-ID")}</span>
                                <span class="mx-2">×</span>
                                <span>${item.quantity}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-primary-600">
                                Rp ${(item.price * item.quantity).toLocaleString(
                                    "id-ID"
                                )}
                            </p>
                        </div>
                    </div>
                </div>
            `
                )
                .join("");
        }

        window.confirmOrder = function() {
            // Show loading state
            const confirmBtn = document.querySelector(
                "#order-preview-modal button[onclick='confirmOrder()']"
            );
            const originalText = confirmBtn.innerHTML;
            confirmBtn.innerHTML =
                '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Memproses...';
            confirmBtn.disabled = true;

            // Submit the order
            submitOrder().finally(() => {
                // Reset button state
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            });
        }

        // Submit order to backend
        async function submitOrder() {
            const form = document.getElementById("checkout-form");
            const formData = new FormData(form);

            // Prepare order data
            const orderData = {
                customer_name: formData.get("customer_name"),
                wa_number: formData.get("wa_number"),
                customer_email: formData.get("customer_email"),
                location_id: formData.get("location_id"),
                pickup_time: formData.get("pickup_time"),
                pick_up_date: formData.get("pick_up_date"),
                note: formData.get("note") || "",
                items: cart.map((item) => ({
                    menu_id: item.id,
                    qty: item.quantity,
                })),
            };

            try {
                const response = await fetch("/order", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute("content") || "",
                    },
                    body: JSON.stringify(orderData),
                });

                const result = await response.json();

                if (result.status === "success") {
                    // Close preview modal
                    closeOrderPreview();
                    
                    console.log('Order creation successful:', result);
                    console.log('QR Code:', result.qr_code);
                    console.log('Pickup Code:', result.pickup_code);
                    
                    // Open Midtrans payment popup
                    if (result.snap_token) {
                        window.snap.pay(result.snap_token, {
                            onSuccess: function(midtransResult) {
                                console.log('Payment success:', midtransResult);
                                // Clear cart
                                cart = [];
                                updateCartDisplay();
                                
                                // Close preview modal
                                closeOrderPreview();
                                
                                // Show success modal with QR code - use data from order creation response
                                showOrderSuccessModal(
                                    result.transaction_id,
                                    result.qr_code,
                                    result.pickup_code
                                );
                            },
                            onPending: function(midtransResult) {
                                console.log('Payment pending:', midtransResult);
                                // Clear cart
                                cart = [];
                                updateCartDisplay();
                                
                                // Close preview modal
                                closeOrderPreview();
                                
                                // Show success modal with QR code even for pending payments
                                showOrderSuccessModal(
                                    result.transaction_id,
                                    result.qr_code,
                                    result.pickup_code
                                );
                            },
                            onError: function(midtransResult) {
                                console.log('Payment error:', midtransResult);
                                alert('Terjadi kesalahan dalam pembayaran. Silakan coba lagi.');
                            },
                            onClose: function() {
                                console.log('Payment popup closed');
                                alert('Pembayaran dibatalkan.');
                            }
                        });
                    } else {
                        alert('Error: Snap token tidak tersedia');
                    }
                } else {
                    alert(result.message || 'Terjadi kesalahan saat memproses pesanan.');
                }
            } catch (error) {
                console.error("Order submission error:", error);
                alert("Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.");
                throw error;
            }
        }

        // Function to show order success modal with QR Code
        function showOrderSuccessModal(transactionId, qrCodeDataUri, pickupCode) {
            // Create modal HTML if it doesn't exist
            let modal = document.getElementById("order-success-modal");
            if (!modal) {
                const modalHTML = `
                    <div id="order-success-modal" class="fixed inset-0 bg-neutral-900/25 z-70 hidden items-center justify-center p-4">
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                            <!-- Modal Header -->
                            <div class="bg-green-500 text-white p-4 rounded-t-lg">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold">Pesanan Berhasil!</h2>
                                    <button onclick="closeOrderSuccessModal()" class="text-white hover:text-gray-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Modal Content -->
                            <div class="p-6 text-center">
                                <!-- Success Icon -->
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>

                                <h3 class="text-lg font-medium text-gray-900 mb-2">Pembayaran Berhasil!</h3>
                                <p class="text-sm text-gray-600 mb-4">Terima kasih atas pesanan Anda. Berikut adalah QR code untuk pickup:</p>

                                <!-- Order Info -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <div class="text-sm">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">Nomor Pesanan:</span>
                                            <span class="font-medium" id="order-number">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kode Pickup:</span>
                                            <span class="font-bold text-primary-600" id="pickup-code">-</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Code -->
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Scan QR Code untuk pickup:</p>
                                    <div class="flex justify-center">
                                        <img id="qr-code-image" src="" alt="QR Code" class="w-48 h-48 border border-gray-200 rounded-lg">
                                    </div>
                                </div>

                                <!-- Instructions -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-left">
                                            <p class="text-sm font-medium text-blue-800">Petunjuk Pickup:</p>
                                            <ul class="text-sm text-blue-700 mt-1 list-disc list-inside">
                                                <li>Datang ke lokasi pickup sesuai jadwal</li>
                                                <li>Tunjukkan QR code atau sebutkan kode pickup</li>
                                                <li>Screenshot atau simpan QR code ini</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <button 
                                    onclick="closeOrderSuccessModal()"
                                    class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition font-medium">
                                    Selesai
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                modal = document.getElementById("order-success-modal");
            }

            // Update modal content
            document.getElementById("order-number").textContent = transactionId;
            document.getElementById("pickup-code").textContent = pickupCode || "";
            
            const qrImage = document.getElementById("qr-code-image");
            if (qrCodeDataUri && qrCodeDataUri !== '') {
                qrImage.src = qrCodeDataUri;
                qrImage.style.display = 'block';
            } else {
                qrImage.style.display = 'none';
            }

            // Show modal
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }

        // Function to close order success modal
        window.closeOrderSuccessModal = function() {
            const modal = document.getElementById("order-success-modal");
            if (modal) {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
                
                // Reload page after closing modal
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        }

    </script>
</body>
</html>