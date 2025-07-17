<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lokasi - CalsFine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">
    
    @include('admin.partials.navbar')

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

        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kelola Lokasi</h1>
                    <p class="text-gray-600 mt-1">Atur lokasi pickup untuk customer CalsFine</p>
                </div>
                <a href="{{ route('admin.locations.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Lokasi
                </a>
            </div>
        </div>

        <!-- Locations List -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($locations as $location)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $location->name }}</div>
                                        @if($location->operating_hours)
                                            <div class="text-sm text-gray-500">{{ $location->operating_hours }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs">
                                    {{ Str::limit($location->address, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($location->contact_person)
                                        <div>{{ $location->contact_person }}</div>
                                    @endif
                                    @if($location->contact_phone)
                                        <div class="text-gray-500">{{ $location->contact_phone }}</div>
                                    @endif
                                    @if(!$location->contact_person && !$location->contact_phone)
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $location->transactions_count }} transaksi</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.locations.show', $location) }}" class="text-blue-600 hover:text-blue-900">
                                        Lihat
                                    </a>
                                    <a href="{{ route('admin.locations.edit', $location) }}" class="text-yellow-600 hover:text-yellow-900">
                                        Edit
                                    </a>
                                    @if($location->transactions_count == 0)
                                        <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400" title="Tidak dapat dihapus karena masih memiliki transaksi">
                                            Hapus
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada lokasi ditemukan. <a href="{{ route('admin.locations.create') }}" class="text-green-500 hover:text-green-600">Tambah lokasi pertama</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($locations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $locations->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
