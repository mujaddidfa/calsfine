<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu: {{ $menu->name }} - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom dropdown styling consistent with analytics */
        .dropdown-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25rem 1.25rem;
        }
        
        .dropdown-select:hover {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234b5563' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        }
        
        .dropdown-select:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23752727' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        }
    </style>
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex items-center">
                @if(($referrer ?? 'show') === 'index')
                    <a href="{{ route('admin.menus') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('admin.menus.show', $menu) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">Edit Menu</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi menu {{ $menu->name }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Hidden field to track where user came from -->
                <input type="hidden" name="from" value="{{ $referrer ?? 'show' }}">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Dasar -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Informasi Dasar</h3>
                        
                        <!-- Nama Menu -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Menu *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $menu->name) }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                                   placeholder="Misal: Nasi Gudeg Yogya" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                            <select id="category_id" name="category_id" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category_id') border-red-500 @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $menu->category_id) == $category->id) ? 'selected' : '' }}>
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
                                <input type="number" id="price" name="price" value="{{ old('price', number_format($menu->price, 0, '', '')) }}" 
                                       class="w-full pl-12 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('price') border-red-500 @enderror" 
                                       placeholder="25000" min="0" pattern="[0-9]*"
                                       oninput="removeLeadingZeros(this)" required>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stok *</label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', $menu->stock) }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('stock') border-red-500 @enderror" 
                                   min="0" step="1" pattern="[0-9]*" 
                                   oninput="removeLeadingZeros(this)" required>
                            <p class="mt-1 text-xs text-gray-500">Masukkan jumlah stok yang tersedia</p>
                            @error('stock')
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
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror" 
                                      placeholder="Deskripsi singkat tentang menu ini...">{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image or Preview -->
                        <div id="image-preview-container" style="display: {{ $menu->image ? 'block' : 'none' }};">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span id="current-image-title">{{ $menu->image ? 'Gambar Menu' : 'Preview Gambar' }}</span>
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <img id="current-img" src="{{ $menu->image ? asset('storage/' . $menu->image) : '' }}" alt="{{ $menu->name }}" class="h-20 w-20 object-cover rounded-lg border {{ $menu->image ? 'border-gray-300' : 'border-green-500' }}">
                                </div>
                                <div>
                                    <p id="current-image-desc" class="text-sm text-gray-600">{{ $menu->image ? 'Gambar menu yang sedang aktif' : 'Preview gambar yang akan diupload' }}</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @if($menu->image)
                                        <button type="button" onclick="toggleRemoveImage()" id="remove-btn" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 inline-flex items-center">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span id="remove-btn-text">Hapus Gambar</span>
                                        </button>
                                        <button type="button" onclick="cancelRemoveImage()" id="cancel-btn" class="bg-orange-500 hover:bg-orange-600 text-white text-xs px-4 py-1.5 rounded-lg font-medium transition-colors duration-200 items-center hidden">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Batal Hapus
                                        </button>
                                        @endif
                                        <button type="button" onclick="resetToOriginal()" id="reset-btn" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 ml-2 hidden">
                                            {{ $menu->image ? 'Kembalikan Asli' : 'Hapus Preview' }}
                                        </button>
                                        <input type="hidden" name="remove_image" id="remove_image_input" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Gambar Baru -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($menu->image)
                                    Ganti Gambar Menu
                                @else
                                    Gambar Menu
                                @endif
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload file</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t">
                    @if(($referrer ?? 'show') === 'index')
                        <a href="{{ route('admin.menus') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium cursor-pointer">
                            Batal
                        </a>
                    @else
                        <a href="{{ route('admin.menus.show', $menu) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium cursor-pointer">
                            Batal
                        </a>
                    @endif
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg font-medium cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Store original image source
        const originalImageSrc = "{{ $menu->image ? asset('storage/' . $menu->image) : '' }}";
        const hasOriginalImage = {{ $menu->image ? 'true' : 'false' }};
        let hasNewImage = false;
        
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Show preview container if hidden
                    const previewContainer = document.getElementById('image-preview-container');
                    previewContainer.style.display = 'block';
                    
                    // Update current image with new preview
                    const currentImg = document.getElementById('current-img');
                    const currentImageTitle = document.getElementById('current-image-title');
                    const currentImageDesc = document.getElementById('current-image-desc');
                    const resetBtn = document.getElementById('reset-btn');
                    
                    if (currentImg) {
                        // Update current image to show new image
                        currentImg.src = e.target.result;
                        currentImg.classList.add('border-green-500');
                        currentImg.classList.remove('border-gray-300', 'border-red-500');
                        
                        // Update labels
                        if (hasOriginalImage) {
                            currentImageTitle.textContent = 'Preview Gambar Baru';
                            currentImageDesc.innerHTML = '<span class="text-green-600 font-medium">Gambar baru siap untuk disimpan</span>';
                        } else {
                            currentImageTitle.textContent = 'Preview Gambar';
                            currentImageDesc.innerHTML = '<span class="text-green-600 font-medium">Gambar siap untuk diupload</span>';
                        }
                        
                        // Show reset button
                        resetBtn.classList.remove('hidden');
                        resetBtn.textContent = hasOriginalImage ? 'Kembalikan Asli' : 'Hapus Preview';
                        
                        hasNewImage = true;
                    }
                };
                reader.readAsDataURL(input.files[0]);
                
                // Auto-cancel remove image if user selects new image
                if (document.getElementById('remove_image_input').value === '1') {
                    cancelRemoveImage();
                }
            }
        }

        function resetToOriginal() {
            const currentImg = document.getElementById('current-img');
            const currentImageTitle = document.getElementById('current-image-title');
            const currentImageDesc = document.getElementById('current-image-desc');
            const resetBtn = document.getElementById('reset-btn');
            const previewContainer = document.getElementById('image-preview-container');
            const input = document.getElementById('image');
            
            if (hasOriginalImage && originalImageSrc) {
                // Reset to original image
                currentImg.src = originalImageSrc;
                currentImg.classList.remove('border-green-500', 'border-red-500');
                currentImg.classList.add('border-gray-300');
                currentImg.style.opacity = '1';
                currentImg.style.filter = 'none';
                
                // Reset labels
                currentImageTitle.textContent = 'Gambar Menu';
                currentImageDesc.textContent = 'Gambar menu yang sedang aktif';
                
                // Hide reset button
                resetBtn.classList.add('hidden');
            } else {
                // Hide preview container if no original image
                previewContainer.style.display = 'none';
            }
            
            // Clear file input
            input.value = '';
            
            hasNewImage = false;
        }

        function toggleRemoveImage() {
            const removeBtn = document.getElementById('remove-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            const resetBtn = document.getElementById('reset-btn');
            const removeInput = document.getElementById('remove_image_input');
            const currentImg = document.getElementById('current-img');
            const currentImageTitle = document.getElementById('current-image-title');
            const currentImageDesc = document.getElementById('current-image-desc');
            
            // Set remove flag
            removeInput.value = '1';
            
            // Update button states - hide remove button and show cancel button
            removeBtn.style.display = 'none';
            cancelBtn.style.display = 'inline-flex';
            cancelBtn.classList.remove('hidden');
            cancelBtn.classList.add('inline-flex');
            
            // Hide reset button if visible
            resetBtn.classList.add('hidden');
            
            // Update title and description
            currentImageTitle.textContent = 'Gambar Akan Dihapus';
            currentImageDesc.innerHTML = '<span class="text-red-600 font-medium">Gambar ini akan dihapus saat form disimpan</span>';
            
            // Show visual feedback on image
            if (currentImg) {
                currentImg.style.opacity = '0.4';
                currentImg.style.filter = 'grayscale(100%)';
                currentImg.classList.remove('border-green-500', 'border-gray-300');
                currentImg.classList.add('border-red-500');
            }
        }

        function cancelRemoveImage() {
            const removeBtn = document.getElementById('remove-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            const resetBtn = document.getElementById('reset-btn');
            const removeInput = document.getElementById('remove_image_input');
            const currentImg = document.getElementById('current-img');
            const currentImageTitle = document.getElementById('current-image-title');
            const currentImageDesc = document.getElementById('current-image-desc');
            
            // Reset remove flag
            removeInput.value = '0';
            
            // Update button states - show remove button and hide cancel button
            removeBtn.style.display = 'inline-flex';
            removeBtn.classList.remove('hidden');
            removeBtn.classList.add('inline-flex');
            cancelBtn.style.display = 'none';
            cancelBtn.classList.add('hidden');
            cancelBtn.classList.remove('inline-flex');
            
            // Show reset button if there's a new image
            if (hasNewImage) {
                resetBtn.classList.remove('hidden');
                // Restore preview state
                currentImageTitle.textContent = 'Preview Gambar Baru';
                currentImageDesc.innerHTML = '<span class="text-green-600 font-medium">Gambar baru siap untuk disimpan</span>';
                currentImg.classList.remove('border-red-500');
                currentImg.classList.add('border-green-500');
            } else {
                // Restore original state
                currentImageTitle.textContent = 'Gambar Menu';
                currentImageDesc.textContent = 'Gambar menu yang sedang aktif';
                currentImg.classList.remove('border-red-500', 'border-green-500');
                currentImg.classList.add('border-gray-300');
            }
            
            // Remove visual feedback
            if (currentImg) {
                currentImg.style.opacity = '1';
                currentImg.style.filter = 'none';
            }
        }

        function removeLeadingZeros(input) {
            let value = input.value;
            
            // Remove leading zeros
            if (value.length > 1 && value.startsWith('0')) {
                value = value.replace(/^0+/, '');
                // If all zeros, keep one zero
                if (value === '') {
                    value = '0';
                }
                input.value = value;
            }
            
            // Ensure only positive integers
            if (value < 0) {
                input.value = 0;
            }
        }
    </script>
</body>
</html>
