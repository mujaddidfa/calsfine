<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">
    @include('admin.partials.navbar')

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">History & Analytics Penjualan</h1>
                    <p class="text-gray-600 mt-1">Tracking penjualan dan analisa performa bisnis CalsFine</p>
                </div>
                <div class="flex items-center space-x-4">
                    <select id="period-filter" onchange="filterPeriod()" class="bg-white border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="daily">Harian</option>
                        <option value="weekly">Mingguan</option>
                        <option value="monthly">Bulanan</option>
                        <option value="yearly">Tahunan</option>
                    </select>
                    <input type="date" id="date-filter" onchange="filterDate()" class="bg-white border border-gray-300 rounded-md px-3 py-2 text-sm" value="{{ now()->format('Y-m-d') }}">
                    <button onclick="exportData()" class="text-green-600 hover:text-green-700 font-medium text-sm bg-green-50 px-4 py-2 rounded-md border border-green-200">
                        Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-600">Total Pesanan</p>
                        <p class="text-2xl font-bold text-blue-900" id="total-orders">{{ $stats['total_orders'] ?? 0 }}</p>
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
                        <p class="text-sm font-medium text-green-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-green-900" id="total-revenue">Rp {{ number_format($stats['today_revenue'] ?? 0) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600">Rata-rata/Hari</p>
                        <p class="text-2xl font-bold text-yellow-900" id="avg-daily">Rp {{ number_format(($stats['today_revenue'] ?? 0)) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-600">Success Rate</p>
                        <p class="text-2xl font-bold text-purple-900" id="success-rate">{{ $stats['pickup_rate'] ?? 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Data History Penjualan</h3>
                <p class="text-gray-600 text-sm">Riwayat transaksi dan performa penjualan berdasarkan periode yang dipilih</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="analytics-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Top Menu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
        function filterPeriod() {
            loadAnalyticsData();
        }

        function filterDate() {
            loadAnalyticsData();
        }

        function loadAnalyticsData() {
            const period = document.getElementById('period-filter').value;
            const date = document.getElementById('date-filter').value;
            
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
            fetch(`{{ route('admin.history.data') }}?period=${period}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayAnalyticsData(data.data);
                        updateSummaryCardsFromAPI(data.summary);
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

        function displayAnalyticsData(data) {
            const tbody = document.getElementById('analytics-tbody');
            
            if (data.length === 0) {
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

            tbody.innerHTML = data.map(item => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.date}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.orders} pesanan</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">Rp ${item.revenue.toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-green-600 h-2 rounded-full" style="width: ${item.pickup_rate}%"></div>
                            </div>
                            <span class="text-sm text-gray-900">${item.pickup_rate}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.top_menu}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${item.status === 'success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            ${item.status === 'success' ? 'Baik' : 'Perlu Perhatian'}
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        function updateSummaryCardsFromAPI(summary) {
            document.getElementById('total-orders').textContent = summary.total_orders;
            document.getElementById('total-revenue').textContent = `Rp ${summary.total_revenue.toLocaleString('id-ID')}`;
            document.getElementById('avg-daily').textContent = `Rp ${Math.floor(summary.total_revenue / 7).toLocaleString('id-ID')}`;
            document.getElementById('success-rate').textContent = `${Math.round(summary.avg_pickup_rate)}%`;
        }

        function exportData() {
            const period = document.getElementById('period-filter').value;
            const date = document.getElementById('date-filter').value;
            
            // Create download link for export
            const exportUrl = `{{ route('admin.history.data') }}?period=${period}&date=${date}&export=excel`;
            window.open(exportUrl, '_blank');
        }

        function previousPage() {
            // Implement pagination
            console.log('Previous page');
        }

        function nextPage() {
            // Implement pagination  
            console.log('Next page');
        }

        // Load initial analytics data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadAnalyticsData();
        });
    </script>
</body>
</html>
