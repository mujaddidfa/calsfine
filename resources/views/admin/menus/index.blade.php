<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kelola Menu</h1>
                    <p class="text-gray-600 mt-1">Tambah, edit, dan hapus menu makanan CalsFine</p>
                </div>
                <a href="{{ route('admin.menus.create') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Menu
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Kategori</label>
                    <select name="category" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
                <a href="{{ route('admin.menus') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Reset
                </a>
            </form>
        </div>

        <!-- Menu List -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($menus as $menu)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($menu->image)
                                        <img class="h-12 w-12 rounded-lg object-cover mr-4" src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $menu->name }}</div>
                                        @if($menu->description)
                                            <div class="text-sm text-gray-500 max-w-xs truncate">{{ $menu->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $menu->category->name ?? 'Tidak ada kategori' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.menus.show', $menu) }}" class="text-blue-600 hover:text-blue-900">
                                        Lihat
                                    </a>
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="text-yellow-600 hover:text-yellow-900">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada menu ditemukan. <a href="{{ route('admin.menus.create') }}" class="text-primary-500 hover:text-primary-600">Tambah menu pertama</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($menus->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $menus->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
