<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lokasi - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Success Messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <a href="{{ request('referrer') === 'show' ? route('admin.locations.show', $location) : route('admin.locations') }}" 
                   class="text-gray-500 hover:text-gray-700 mr-4 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Lokasi</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi lokasi "{{ $location->name }}"</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form id="editLocationForm" action="{{ route('admin.locations.update', $location) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="referrer" value="{{ request('referrer') }}">
                
                <div class="space-y-6">
                    <!-- Nama Lokasi -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lokasi *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $location->name) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                               placeholder="Misal: Kampus IPB, Mall Bogor Junction" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Lokasi -->
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Link Lokasi *</label>
                        <input type="url" id="url" name="url" value="{{ old('url', $location->url) }}"
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
                        <div class="space-y-3" id="pickupTimesEditWrapper">
                            @foreach($location->pickupTimes as $i => $pickupTime)
                                <div class="flex items-center space-x-2" id="pickup_time_field_edit_{{ $pickupTime->id }}">
                                    <input type="time" name="pickup_times_existing[{{ $pickupTime->id }}]" value="{{ $pickupTime->formatted_time }}" class="border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <button type="button" onclick="removePickupTimeFieldEdit({{ $pickupTime->id }}, true)" class="text-red-600 hover:text-red-800 text-sm cursor-pointer">Hapus</button>
                                </div>
                            @endforeach
                            <div id="additionalPickupTimesEdit"></div>
                            <div class="flex items-center space-x-2 mt-2">
                                <input type="time" id="pickup_time_edit_new" name="pickup_times[]" class="border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Pilih jam">
                                <button type="button" onclick="addPickupTimeFieldEdit()" class="text-primary-600 hover:text-primary-800 text-sm cursor-pointer">+ Tambah jam lain</button>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Edit, hapus, atau tambahkan jam pickup untuk lokasi ini</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ request('referrer') === 'show' ? route('admin.locations.show', $location) : route('admin.locations') }}" 
                       class="bg-slate-300 hover:bg-slate-400 text-slate-800 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 cursor-pointer">
                        Perbarui Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-hide success messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });

        // Dynamic pickup time fields for edit
        let pickupTimeEditCounter = 0;
        function addPickupTimeFieldEdit() {
            pickupTimeEditCounter++;
            const additionalContainer = document.getElementById('additionalPickupTimesEdit');
            const newField = document.createElement('div');
            newField.className = 'flex items-center space-x-2';
            newField.id = `pickup_time_field_edit_new_${pickupTimeEditCounter}`;
            newField.innerHTML = `
                <input type="time" name="pickup_times[]" class="border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Pilih jam">
                <button type="button" onclick="removePickupTimeFieldEdit('new_${pickupTimeEditCounter}')" class="text-red-600 hover:text-red-800 text-sm cursor-pointer">Hapus</button>
            `;
            additionalContainer.appendChild(newField);
        }
        function removePickupTimeFieldEdit(id, isExisting = false) {
            let field = document.getElementById(`pickup_time_field_edit_${id}`);
            if (!field) field = document.getElementById(`pickup_time_field_edit_new_${id}`);
            if (field) field.remove();
            // Jika yang dihapus adalah pickup time existing, tambahkan input hidden agar controller tahu untuk menghapus di DB
            if (isExisting) {
                const form = document.getElementById('editLocationForm');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'pickup_times_delete[]';
                input.value = id;
                form.appendChild(input);
            }
        }
    </script>
</body>
</html>
