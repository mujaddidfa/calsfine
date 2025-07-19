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

                    <!-- Alamat -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat *</label>
                        <textarea id="address" name="address" rows="3" 
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('address') border-red-500 @enderror" 
                                  placeholder="Alamat lengkap lokasi pickup..." required>{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kontak Person -->
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('contact_person') border-red-500 @enderror" 
                               placeholder="Nama penanggung jawab lokasi">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('contact_phone') border-red-500 @enderror" 
                               placeholder="08xxxxxxxxxx">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Operasional -->
                    <div>
                        <label for="operating_hours" class="block text-sm font-medium text-gray-700 mb-2">Jam Operasional</label>
                        <input type="text" id="operating_hours" name="operating_hours" value="{{ old('operating_hours') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('operating_hours') border-red-500 @enderror" 
                               placeholder="Misal: Senin-Jumat 08:00-17:00">
                        @error('operating_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Lokasi -->
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Link Lokasi</label>
                        <input type="url" id="url" name="url" value="{{ old('url') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('url') border-red-500 @enderror" 
                               placeholder="https://maps.google.com/... atau https://goo.gl/maps/...">
                        <p class="mt-1 text-xs text-gray-500">Link Google Maps atau platform peta lainnya untuk memudahkan customer menemukan lokasi</p>
                        @error('url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.locations') }}" class="bg-slate-300 hover:bg-slate-400 text-slate-800 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 cursor-pointer">
                        Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>