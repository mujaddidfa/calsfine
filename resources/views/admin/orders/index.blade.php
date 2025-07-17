<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan Hari Ini - CalsFine Admin</title>
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

        <!-- Header & Tabs -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kelola Pesanan Hari Ini</h1>
                    <p class="text-gray-600 mt-1">{{ now()->format('d F Y') }} - Kelola pickup dan status pesanan</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.orders.tomorrow') }}" class="bg-secondary-500 hover:bg-secondary-600 text-white px-4 py-2 rounded-lg font-medium">
                        Lihat Pesanan Besok
                    </a>
                    <a href="{{ route('admin.orders.report') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium">
                        Laporan
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-blue-600 text-sm font-medium">Total Pesanan</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $orders->total() }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-green-600 text-sm font-medium">Sudah Diambil</div>
                    <div class="text-2xl font-bold text-green-900">{{ $orders->where('status', 'completed')->count() }}</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-yellow-600 text-sm font-medium">Menunggu Pickup</div>
                    <div class="text-2xl font-bold text-yellow-900">{{ $orders->where('status', 'paid')->count() }}</div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <div class="text-red-600 text-sm font-medium">Tidak Diambil</div>
                    <div class="text-2xl font-bold text-red-900">{{ $orders->where('status', 'cancelled')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="all">Semua Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Menunggu Pickup</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Sudah Diambil</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Tidak Diambil</option>
                    </select>
                </div>
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Lokasi</label>
                    <select name="location" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="all">Semua Lokasi</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
                <a href="{{ route('admin.orders') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Reset
                </a>
            </form>
        </div>

        <!-- Orders List -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 {{ $order->status == 'paid' ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->status == 'paid')
                                    <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox rounded border-gray-300">
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                    @if($order->note)
                                        <div class="text-xs text-gray-400 mt-1">{{ Str::limit($order->note, 30) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                    @if($order->wa_number)
                                        <div class="text-sm text-gray-500">{{ $order->wa_number }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $order->location->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                <div class="text-xs text-gray-500">{{ $order->transactionItems->count() }} item</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->status == 'paid')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu Pickup
                                    </span>
                                @elseif($order->status == 'completed')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Sudah Diambil
                                    </span>
                                @elseif($order->status == 'cancelled')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Tidak Diambil
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                                        Detail
                                    </a>
                                    @if($order->status == 'paid')
                                        <form action="{{ route('admin.orders.completed', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Tandai pesanan sebagai sudah diambil?')">
                                                Selesai
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.orders.cancelled', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tandai pesanan sebagai tidak diambil?')">
                                                Tidak Diambil
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada pesanan untuk hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif
        </div>

        <!-- Bulk Actions -->
        <div id="bulk-actions" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white shadow-lg rounded-lg border p-4 hidden">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">
                    <span id="selected-count">0</span> pesanan dipilih
                </span>
                <button onclick="bulkAction('completed')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                    Tandai Selesai
                </button>
                <button onclick="bulkAction('cancelled')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                    Tandai Tidak Diambil
                </button>
                <button onclick="clearSelection()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <script>
        // Bulk selection logic
        const selectAll = document.getElementById('select-all');
        const orderCheckboxes = document.querySelectorAll('.order-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');

        function updateBulkActions() {
            const checked = document.querySelectorAll('.order-checkbox:checked').length;
            selectedCount.textContent = checked;
            
            if (checked > 0) {
                bulkActions.classList.remove('hidden');
            } else {
                bulkActions.classList.add('hidden');
            }
        }

        selectAll.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        orderCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function bulkAction(action) {
            const checkedOrders = document.querySelectorAll('.order-checkbox:checked');
            if (checkedOrders.length === 0) return;

            const orderIds = Array.from(checkedOrders).map(cb => cb.value);
            const actionText = action === 'completed' ? 'selesai' : 'tidak diambil';
            
            if (confirm(`Tandai ${orderIds.length} pesanan sebagai ${actionText}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.orders.bulk-update") }}';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = csrfToken.getAttribute('content');
                    form.appendChild(csrf);
                }

                orderIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'order_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = action;
                form.appendChild(actionInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function clearSelection() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            selectAll.checked = false;
            updateBulkActions();
        }
    </script>
</body>
</html>
