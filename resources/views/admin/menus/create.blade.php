<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.menus') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Menu Baru</h1>
                    <p class="text-gray-600 mt-1">Tambahkan menu makanan baru ke CalsFine</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Dasar -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Informasi Dasar</h3>
                        
                        <!-- Nama Menu -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Menu *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror" 
                                   placeholder="Misal: Nasi Gudeg Yogya" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                            <select id="category_id" name="category_id" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('category_id') border-red-500 @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" id="price" name="price" value="{{ old('price') }}" 
                                       class="w-full pl-12 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('price') border-red-500 @enderror" 
                                       placeholder="25000" min="0" step="500" required>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi dan Gambar -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Detail Menu</h3>
                        
                        <!-- Deskripsi -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea id="description" name="description" rows="4" 
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('description') border-red-500 @enderror" 
                                      placeholder="Deskripsi singkat tentang menu ini...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Gambar -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Menu</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-primary-400">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>Upload file</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview Image -->
                            <div id="image-preview" class="mt-4 hidden">
                                <img id="preview-img" class="h-32 w-32 object-cover rounded-lg" src="" alt="Preview">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.menus') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg font-medium">
                        Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
