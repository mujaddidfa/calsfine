<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Tertunda - CalsFine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-yellow-500 text-white p-6 text-center">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold">Pembayaran Tertunda</h1>
                <p class="text-yellow-100 mt-2">Menunggu konfirmasi pembayaran</p>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Pesanan Sedang Diproses</h2>
                    @if(isset($orderId))
                        <p class="text-gray-600">Order ID: <span class="font-mono text-primary-600">{{ $orderId }}</span></p>
                    @endif
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-yellow-800 mb-2">Status Pembayaran:</h3>
                    <p class="text-sm text-yellow-700 mb-3">
                        Pembayaran Anda sedang diproses. Ini bisa memakan waktu beberapa menit.
                    </p>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Jangan tutup halaman ini</li>
                        <li>• Tunggu konfirmasi dari bank/payment gateway</li>
                        <li>• Kami akan mengirim notifikasi WhatsApp setelah pembayaran dikonfirmasi</li>
                    </ul>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-700">
                        <strong>Butuh bantuan?</strong><br>
                        Hubungi customer service kami jika pembayaran tidak terkonfirmasi dalam 1x24 jam.
                    </p>
                </div>

                <div class="space-y-3">
                    <button onclick="checkPaymentStatus()" class="w-full bg-primary-500 text-white py-3 px-4 rounded-lg hover:bg-primary-600 transition font-semibold">
                        Cek Status Pembayaran
                    </button>
                    <a href="{{ route('home') }}" class="block w-full bg-gray-200 text-gray-800 text-center py-3 px-4 rounded-lg hover:bg-gray-300 transition font-medium">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4">
                <p class="text-xs text-gray-500 text-center">
                    Status akan diperbarui otomatis setelah pembayaran dikonfirmasi
                </p>
            </div>
        </div>
    </div>

    <script>
        function checkPaymentStatus() {
            const orderId = '{{ $orderId ?? "" }}';
            if (!orderId) {
                alert('Order ID tidak ditemukan');
                return;
            }

            // Extract transaction ID from order ID (ORDER-{id}-{timestamp})
            const parts = orderId.split('-');
            const transactionId = parts[1];

            if (!transactionId) {
                alert('Transaction ID tidak valid');
                return;
            }

            // Check payment status
            fetch(`/payment/status/${transactionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        if (data.payment_status === 'paid') {
                            alert('Pembayaran sudah berhasil! Halaman akan dimuat ulang.');
                            window.location.href = '{{ route("payment.finish") }}?order_id=' + orderId + '&transaction_status=settlement';
                        } else {
                            alert('Status: ' + data.payment_status + '. Pembayaran masih dalam proses.');
                        }
                    } else {
                        alert('Tidak dapat memeriksa status pembayaran. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memeriksa status pembayaran.');
                });
        }

        // Auto refresh every 30 seconds
        setInterval(checkPaymentStatus, 30000);
    </script>
</body>
</html>
