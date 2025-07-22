<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Gagal - CalsFine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-red-500 text-white p-6 text-center">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold">Pembayaran Gagal</h1>
                <p class="text-red-100 mt-2">Transaksi tidak dapat diproses</p>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Oops! Ada Masalah</h2>
                    @if(isset($orderId))
                        <p class="text-gray-600">Order ID: <span class="font-mono text-primary-600">{{ $orderId }}</span></p>
                    @endif
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-red-800 mb-2">Kemungkinan Penyebab:</h3>
                    <ul class="text-sm text-red-700 space-y-1">
                        <li>• Saldo atau limit kartu tidak mencukupi</li>
                        <li>• Koneksi internet terputus</li>
                        <li>• Data pembayaran tidak valid</li>
                        <li>• Transaksi dibatalkan atau timeout</li>
                    </ul>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-800 mb-2">Apa yang bisa dilakukan?</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Coba lakukan pemesanan ulang</li>
                        <li>• Periksa saldo atau gunakan metode pembayaran lain</li>
                        <li>• Pastikan koneksi internet stabil</li>
                        <li>• Hubungi customer service jika masalah berlanjut</li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('order') }}" class="block w-full bg-primary-500 text-white text-center py-3 px-4 rounded-lg hover:bg-primary-600 transition font-semibold">
                        Coba Pesan Lagi
                    </a>
                    <a href="{{ route('home') }}" class="block w-full bg-gray-200 text-gray-800 text-center py-3 px-4 rounded-lg hover:bg-gray-300 transition font-medium">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4">
                <p class="text-xs text-gray-500 text-center">
                    Tidak ada biaya yang dikenakan untuk transaksi yang gagal
                </p>
            </div>
        </div>
    </div>
</body>
</html>
