<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')
                        <a href="{{ route('admin.locations') }}" class="text-white hover:text-secondary-500 px-3 py-2 rounded-md text-sm font-medium">Lokasi</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <form action="/admin/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-secondary-500 text-sm font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.categories') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Kategori Baru</h1>
                    <p class="text-gray-600 mt-1">Buat kategori baru untuk mengorganisir menu makanan</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Nama Kategori -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                               placeholder="Misal: Makanan Utama, Minuman, Snack" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror" 
                                  placeholder="Deskripsi singkat tentang kategori ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Kategori aktif dan dapat digunakan</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.categories') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
