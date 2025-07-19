<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lokasi - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.locations') }}" class="text-gray-500 hover:text-gray-700 mr-4 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Lokasi Baru</h1>
                    <p class="text-gray-600 mt-1">Buat lokasi pickup baru untuk customer CalsFine</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.locations.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Nama Lokasi -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lokasi *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror" 
                               placeholder="Misal: Kampus IPB, Mall Bogor Junction" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Lokasi -->
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Link Lokasi *</label>
                        <input type="url" id="url" name="url" value="{{ old('url') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('url') border-red-500 @enderror" 
                               placeholder="https://maps.google.com/... atau https://goo.gl/maps/..." required>
                        <p class="mt-1 text-xs text-gray-500">Link Google Maps atau platform peta lainnya untuk memudahkan customer menemukan lokasi</p>
                        @error('url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Pickup -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Jam Pickup</label>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <input type="time" id="pickup_time_1" name="pickup_times[]" 
                                       class="border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="Pilih jam">
                                <button type="button" onclick="addPickupTimeField()" class="text-primary-600 hover:text-primary-800 text-sm cursor-pointer">
                                    + Tambah jam lain
                                </button>
                            </div>
                            <div id="additionalPickupTimes"></div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Tambahkan jam-jam pickup yang tersedia untuk lokasi ini</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.locations') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition-colors duration-200 cursor-pointer">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 cursor-pointer">
                        Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let pickupTimeCounter = 1;

        function addPickupTimeField() {
            pickupTimeCounter++;
            const additionalContainer = document.getElementById('additionalPickupTimes');
            
            const newField = document.createElement('div');
            newField.className = 'flex items-center space-x-2';
            newField.id = `pickup_time_field_${pickupTimeCounter}`;
            
            newField.innerHTML = `
                <input type="time" id="pickup_time_${pickupTimeCounter}" name="pickup_times[]" 
                       class="border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                       placeholder="Pilih jam">
                <button type="button" onclick="removePickupTimeField(${pickupTimeCounter})" class="text-red-600 hover:text-red-800 text-sm cursor-pointer">
                    Hapus
                </button>
            `;
            
            additionalContainer.appendChild(newField);
        }

        function removePickupTimeField(id) {
            const field = document.getElementById(`pickup_time_field_${id}`);
            if (field) {
                field.remove();
            }
        }
    </script>
</body>
</html>