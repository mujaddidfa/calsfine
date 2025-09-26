<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lokasi - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">
    <style>
        /* Prevent elements from disappearing on click */
        .pickup-time-card {
            position: relative !important;
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .pickup-time-card button {
            position: relative !important;
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            z-index: 1 !important;
        }
        .pickup-time-card:hover {
            transform: none !important;
        }
        /* Smooth transitions for better UX */
        .pickup-time-card button:active {
            transform: scale(0.98) !important;
            transition: transform 0.1s ease-in-out !important;
        }
    </style>
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kelola Lokasi</h1>
                    <p class="text-gray-600 mt-1">Atur lokasi pickup untuk customer CalsFine</p>
                </div>
                <a href="{{ route('admin.locations.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Lokasi
                </a>
            </div>
        </div>

        <!-- Locations List -->
        <div class="space-y-6">
            @forelse($locations as $location)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Location Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $location->name }}</h2>
                                <p class="text-sm text-gray-600 mt-1">{{ $location->address }}</p>
                                @if($location->operating_hours)
                                    <p class="text-sm text-gray-500 mt-1">{{ $location->operating_hours }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if($location->url)
                                <a href="{{ $location->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm inline-flex items-center cursor-pointer transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Maps
                                </a>
                            @endif
                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $location->transactions_count }} transaksi</span>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.locations.edit', $location) }}" class="bg-amber-100 hover:bg-amber-200 text-amber-700 px-3 py-2 rounded-lg text-sm font-medium inline-flex items-center cursor-pointer transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" class="inline" onsubmit="return showDeleteConfirmation(event, '{{ $location->name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg text-sm font-medium inline-flex items-center cursor-pointer transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pickup Times Section -->
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Jam Pickup</h3>
                        <!-- Tombol tambah jam pickup dihapus sesuai permintaan -->
                    </div>
                    
                    @if($location->pickupTimes->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            @foreach($location->pickupTimes as $pickupTime)
                                <div class="pickup-time-card flex items-center justify-center p-3 border rounded-lg transition-all duration-200 bg-blue-50 border-blue-200 text-blue-700 text-sm font-medium">
                                    {{ $pickupTime->formatted_time }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 bg-gray-50 rounded-lg">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 mb-2">Belum ada jam pickup</p>
                            <!-- Tombol tambah jam pickup pertama dihapus sesuai permintaan -->
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-gray-500 mb-2">Tidak ada lokasi ditemukan.</p>
                <a href="{{ route('admin.locations.create') }}" class="text-primary-600 hover:text-primary-800 cursor-pointer">Tambah lokasi pertama</a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($locations->hasPages())
            <div class="mt-6">
                {{ $locations->links() }}
            </div>
        @endif
    </div>

    <!-- Modal tambah jam pickup dihapus sesuai permintaan -->

    <!-- Custom Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full scale-95">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Hapus Lokasi
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus lokasi "<span id="locationNameToDelete" class="font-medium text-gray-700"></span>"? 
                                    Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button onclick="confirmDelete()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Hapus
                    </button>
                    <button onclick="closeDeleteModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentForm = null;

        // Auto-hide success/error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"], .bg-green-50, .bg-red-50');
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

        // Show custom delete confirmation modal
        function showDeleteConfirmation(event, locationName) {
            event.preventDefault();
            currentForm = event.target;
            
            // Update location name in modal
            document.getElementById('locationNameToDelete').textContent = locationName;
            
            // Show modal with animation
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            
            // Add animation
            setTimeout(() => {
                modal.querySelector('.bg-white').classList.add('scale-100');
                modal.querySelector('.bg-gray-500').classList.add('opacity-75');
            }, 10);
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            return false;
        }

        // Close delete modal
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            
            // Remove animation
            modal.querySelector('.bg-white').classList.remove('scale-100');
            modal.querySelector('.bg-gray-500').classList.remove('opacity-75');
            
            // Hide modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                currentForm = null;
            }, 200);
        }

        // Confirm deletion
        function confirmDelete() {
            if (currentForm) {
                // Show loading state
                const confirmBtn = document.querySelector('button[onclick="confirmDelete()"]');
                const originalContent = confirmBtn.innerHTML;
                confirmBtn.innerHTML = `
                    <svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menghapus...
                `;
                confirmBtn.disabled = true;
                
                // Submit form after short delay for UX
                setTimeout(() => {
                    currentForm.submit();
                }, 500);
            }
        }

        // Close modal when ESC is pressed
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
                closeAddPickupModal();
            }
        });

        // Fungsi modal tambah jam pickup dihapus sesuai permintaan

        // Add loading state to pickup time buttons when clicked
        document.addEventListener('DOMContentLoaded', function() {
            const pickupButtons = document.querySelectorAll('button[type="submit"]');
            pickupButtons.forEach(button => {
                if (button.closest('form').action.includes('pickup-times')) {
                    button.addEventListener('click', function(e) {
                        // Add visual feedback without hiding the element
                        this.style.opacity = '0.7';
                        this.style.transform = 'scale(0.95)';
                        
                        // Reset after form submission
                        setTimeout(() => {
                            this.style.opacity = '1';
                            this.style.transform = 'scale(1)';
                        }, 200);
                    });
                }
            });
        });
    </script>
</body>
</html>
