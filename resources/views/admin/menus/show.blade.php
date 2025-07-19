<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu: {{ $menu->name }} - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Action Buttons -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Menu</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap menu {{ $menu->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.menus') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.menus.edit', $menu) }}" class="bg-secondary-500 hover:bg-secondary-600 text-gray-800 px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Menu
                </a>
                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus menu {{ $menu->name }}? Aksi ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Menu Detail Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="lg:flex">
                <!-- Image Section -->
                <div class="lg:w-1/2">
                    @if($menu->image)
                        <img class="h-80 w-full lg:h-full object-cover" src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                    @else
                        <div class="h-80 lg:h-full w-full bg-gray-100 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-500">Tidak ada gambar</p>
                                <p class="text-sm text-gray-400">Gambar belum diupload untuk menu ini</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Details Section -->
                <div class="lg:w-1/2 p-8">
                    <!-- Menu Name -->
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $menu->name }}</h2>
                        @if($menu->description)
                            <p class="text-gray-600 text-lg leading-relaxed">{{ $menu->description }}</p>
                        @else
                            <p class="text-gray-500 italic">Tidak ada deskripsi untuk menu ini</p>
                        @endif
                    </div>

                    <!-- Price & Stock -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="bg-primary-50 rounded-xl p-6 border border-primary-100">
                            <div class="flex items-center">
                                <div class="p-3 rounded-lg bg-primary-500 text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-primary-600">Harga</p>
                                    <p class="text-2xl font-bold text-primary-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-xl p-6 border border-green-100">
                            <div class="flex items-center">
                                <div class="p-3 rounded-lg {{ $menu->stock > 0 ? 'bg-green-500' : 'bg-red-500' }} text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium {{ $menu->stock > 0 ? 'text-green-600' : 'text-red-600' }}">Stok</p>
                                    <p class="text-2xl font-bold {{ $menu->stock > 0 ? 'text-green-900' : 'text-red-900' }}">
                                        {{ $menu->stock > 0 ? $menu->stock . ' tersedia' : 'Habis' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Kategori</h3>
                        <div class="inline-flex items-center">
                            @if($menu->category)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $menu->category->name }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tidak ada kategori
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tambahan</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Dibuat pada:</span>
                                <span class="font-medium text-gray-900">{{ $menu->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Terakhir diubah:</span>
                                <span class="font-medium text-gray-900">{{ $menu->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">ID Menu:</span>
                                <span class="font-medium text-gray-900">#{{ str_pad($menu->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add smooth scroll behavior for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll to top when navigating
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>
