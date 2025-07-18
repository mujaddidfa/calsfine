<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom dropdown styling */
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
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233b82f6' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        }
    </style>
</head>
<body class="bg-gray-50 font-['Poppins']">
    @include('admin.partials.navbar')

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">History & Analytics Penjualan</h1>
                <p class="text-gray-600 mt-1">Tracking penjualan dan analisa bisnis CalsFine</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-600">Total Pesanan (Keseluruhan)</p>
                        <p class="text-2xl font-bold text-blue-900" id="total-orders">{{ $stats['total_orders'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Semua pesanan yang selesai</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Total Revenue (Keseluruhan)</p>
                        <p class="text-2xl font-bold text-green-900" id="total-revenue">Rp {{ number_format($stats['total_revenue'] ?? 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Semua pendapatan sejak awal</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Data History Penjualan</h3>
                        <p class="text-gray-600 text-sm">Riwayat transaksi penjualan berdasarkan periode yang dipilih</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                        <div class="flex flex-col">
                            <label class="text-xs font-medium text-gray-700 mb-1">Periode</label>
                            <select id="period-filter" onchange="filterPeriod()" class="dropdown-select appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-sm font-medium text-gray-700 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 cursor-pointer">
                                <option value="daily">Harian</option>
                                <option value="weekly">Mingguan</option>
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs font-medium text-gray-700 mb-1">Urutkan</label>
                            <select id="sort-filter" onchange="sortData()" class="dropdown-select appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-sm font-medium text-gray-700 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 cursor-pointer">
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="analytics-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diambil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tidak Diambil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Top Menu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="analytics-tbody">
                        <!-- Data akan diload via AJAX -->
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-3 text-lg">Memuat data analytics...</p>
                                    <p class="text-sm">Mohon tunggu sebentar</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <span id="showing-info">Menampilkan data history penjualan</span>
                    </div>
                    <div class="flex items-center space-x-2" id="pagination-controls">
                        <button onclick="previousPage()" class="px-3 py-2 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50">
                            « Previous
                        </button>
                        <span class="text-sm text-gray-600">Page 1 of 1</span>
                        <button onclick="nextPage()" class="px-3 py-2 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50">
                            Next »
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Analytics functions
        let currentPage = 1;
        let totalPages = 1;
        let currentData = [];
        let originalData = []; // Store original data order from API
        const itemsPerPage = 10;

        function filterPeriod() {
            currentPage = 1; // Reset to first page when filter changes
            loadAnalyticsData();
        }

        function sortData() {
            const sortOrder = document.getElementById('sort-filter').value;
            currentPage = 1; // Reset to first page when sorting
            
            // Sort the current data based on selected order
            if (sortOrder === 'newest') {
                currentData = [...originalData]; // Use original order (newest first from API)
            } else {
                currentData = [...originalData].reverse(); // Reverse for oldest first
            }
            
            totalPages = Math.ceil(currentData.length / itemsPerPage);
            displayAnalyticsData();
            updatePaginationInfo();
        }

        function loadAnalyticsData() {
            const period = document.getElementById('period-filter').value;
            
            // Show loading state
            document.getElementById('analytics-tbody').innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-gray-400">
                            <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-3 text-lg">Memuat data analytics...</p>
                            <p class="text-sm">Mohon tunggu sebentar</p>
                        </div>
                    </td>
                </tr>
            `;

            // Make AJAX call to get real data
            fetch(`{{ route('admin.history.data') }}?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Received data:', data); // Debug log
                    if (data.success) {
                        originalData = data.data; // Store original order from API
                        currentData = [...originalData]; // Initialize current data with original order
                        
                        // Apply current sort order
                        const sortOrder = document.getElementById('sort-filter').value;
                        if (sortOrder === 'oldest') {
                            currentData = [...originalData].reverse();
                        }
                        
                        totalPages = Math.ceil(currentData.length / itemsPerPage);
                        displayAnalyticsData();
                        updatePaginationInfo();
                    } else {
                        throw new Error('Failed to load data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('analytics-tbody').innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-red-500">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Error loading data</p>
                                    <p class="text-sm">Please try again or contact administrator</p>
                                </div>
                            </td>
                        </tr>
                    `;
                });
        }

        function displayAnalyticsData() {
            const tbody = document.getElementById('analytics-tbody');
            
            if (currentData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada data</p>
                                <p class="text-sm">Tidak ada data history untuk periode yang dipilih</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            // Calculate start and end index for current page
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = currentData.slice(startIndex, endIndex);

            tbody.innerHTML = pageData.map(item => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.date || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.orders || 0} pesanan</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">Rp ${(item.revenue || 0).toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">${item.completed_orders || 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">${item.cancelled_orders || 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.top_menu || '-'}</td>
                </tr>
            `).join('');
        }

        function updatePaginationInfo() {
            const showingInfo = document.getElementById('showing-info');
            const startItem = (currentPage - 1) * itemsPerPage + 1;
            const endItem = Math.min(currentPage * itemsPerPage, currentData.length);
            
            if (currentData.length === 0) {
                showingInfo.textContent = 'Tidak ada data untuk ditampilkan';
            } else {
                showingInfo.textContent = `Menampilkan ${startItem}-${endItem} dari ${currentData.length} data`;
            }
            
            // Update pagination controls
            const paginationControls = document.querySelector('#pagination-controls span');
            paginationControls.textContent = `Page ${currentPage} of ${totalPages}`;
            
            // Update button states
            const prevButton = document.querySelector('#pagination-controls button:first-child');
            const nextButton = document.querySelector('#pagination-controls button:last-child');
            
            prevButton.disabled = currentPage <= 1;
            nextButton.disabled = currentPage >= totalPages;
            
            if (prevButton.disabled) {
                prevButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                prevButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            
            if (nextButton.disabled) {
                nextButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                nextButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                displayAnalyticsData();
                updatePaginationInfo();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                displayAnalyticsData();
                updatePaginationInfo();
            }
        }

        // Load initial analytics data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadAnalyticsData();
        });
    </script>
</body>
</html>
