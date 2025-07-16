<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Calsfine - Makanan sehat, praktis, dan lezat. Sandwich wanpaku, samyang roll, dan menu cepat saji sehat lainnya.">
    <meta name="keywords" content="makanan sehat, cepat saji, sandwich wanpaku, samyang roll, calsfine">
    <meta name="author" content="Calsfine">
    <title>Calsfine - Makan Sehat, Praktis, dan Lezat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen">
  <nav class="bg-white border-primary-500 border-b shadow-sm sticky top-0 z-50">
    <div class="max-w-screen-xl mx-auto px-4 py-3 flex items-center justify-between">
      <!-- Logo & Tagline -->
      <div class="flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:opacity-80 transition">
          <img src="{{ asset('images/logo.png') }}" alt="CalsFine" class="h-10" />
        </a>
      </div>

      <!-- Nav Links -->
      <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-gray-800">
        <a href="#beranda" class="hover:text-primary-500">Beranda</a>
        <a href="#tentang" class="hover:text-primary-500">Tentang</a>
        <a href="#cara-pesan" class="hover:text-primary-500">Cara Pesan</a>
        <a href="#produk" class="hover:text-primary-500">Produk</a>
        <a href="#pesan" class="border border-primary-500 text-primary-500 px-4 py-1 rounded hover:bg-primary-500 hover:text-white transition">
          Pesan
        </a>
      </div>

      <!-- Burger button (for mobile) -->
      <div class="md:hidden">
        <button data-collapse-toggle="mobile-nav" type="button" class="text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-nav" class="hidden md:hidden px-4 pb-4 space-y-2">
      <a href="#beranda" class="block text-sm text-gray-800">Beranda</a>
      <a href="#tentang" class="block text-sm text-gray-800">Tentang</a>
      <a href="#cara-pesan" class="block text-sm text-gray-800">Cara Pesan</a>
      <a href="#produk" class="block text-sm text-gray-800">Produk</a>
      <a href="#pesan" class="block border border-primary-500 text-primary-500 px-4 py-1 rounded w-fit">Pesan</a>
    </div>
  </nav>
  
  <main class="flex-grow">
    <section class="bg-neutral-50 py-16" id="beranda">
      <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <!-- Ilustrasi Vektor -->
        <div class="flex justify-center order-1 md:order-2">
          <img src="{{ asset('images/hero-illustration.png') }}" alt="Ilustrasi Hero" class="w-full max-w-md">
        </div>

        <!-- Teks -->
        <div class="space-y-6 order-2 md:order-1">
          <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug">
            “Makan Sehat, Praktis, dan Lezat<br /> di Tengah Kesibukanmu”
          </h1>
          <p class="text-gray-700">
            Calsfine menyajikan pilihan makanan cepat saji yang sehat dan bergizi seperti sandwich wanpaku, samyang roll, dan tortilla wrap. Cocok untuk kamu yang aktif dan peduli kesehatan.
          </p>
          <div class="flex space-x-4">
            <a href="#pesan" class="bg-primary-500 text-white px-6 py-2 rounded hover:bg-primary-600 transition">
              Pesan Sekarang
            </a>
            <a href="#produk" class="border border-primary-500 text-primary-500 px-6 py-2 rounded hover:bg-primary-500 hover:text-white transition">
              Lihat produk
            </a>
          </div>
        </div>
      </div>
    </section>
  
    <section class="py-20 bg-white" id="tentang">
      <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <!-- Gambar Ilustrasi Gabungan -->
        <div class="flex justify-center">
          <img src="{{ asset('images/why-calsfine.png') }}" alt="Kenapa Calsfine" class="w-full max-w-md">
        </div>
  
        <!-- Teks -->
        <div>
          <h2 class="text-2xl md:text-3xl font-bold mb-4 text-gray-900">Kenapa Memilih Calsfine?</h2>
          <p class="text-gray-700 leading-relaxed mb-4">
            Calsfine adalah usaha kuliner mikro yang berdiri sejak 2024 dan berfokus pada penyediaan makanan cepat saji yang sehat dan lezat.
          </p>
          <p class="text-gray-700 leading-relaxed mb-4">
            Kami beroperasi dari dapur utama di Puri Serpong dan mendistribusikan produk melalui sistem titip jual di lokasi strategis seperti koperasi sekolah dan stasiun.
          </p>
          <p class="text-gray-700 leading-relaxed">
            Kami percaya, makanan sehat tidak harus ribet. Maka dari itu, kami hadirkan solusi makan cepat yang tetap bernutrisi.
          </p>
        </div>
      </div>
    </section>
  
    <section class="bg-neutral-50 py-20" id="cara-pesan">
      <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-12">Cara Pemesanan</h2>
  
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10 text-lg">
          <!-- Langkah 1 -->
          <div class="flex flex-col items-center">
            <img src="{{ asset('images/step-1.png') }}" alt="Pilih Menu" class="w-24 h-24 mb-4">
            <p class="font-semibold">1. Pilih Menu Favoritmu</p>
          </div>
  
          <!-- Langkah 2 -->
          <div class="flex flex-col items-center">
            <img src="{{ asset('images/step-2.png') }}" alt="Isi Data" class="w-24 h-24 mb-4">
            <p class="font-semibold">2. Isi Data Diri</p>
          </div>
  
          <!-- Langkah 3 -->
          <div class="flex flex-col items-center">
            <img src="{{ asset('images/step-3.png') }}" alt="Bayar" class="w-24 h-24 mb-4">
            <p class="font-semibold">3. Lakukan Pembayaran</p>
          </div>
  
          <!-- Langkah 4 -->
          <div class="flex flex-col items-center">
            <img src="{{ asset('images/step-4.png') }}" alt="QR WA" class="w-24 h-24 mb-4">
            <p class="font-semibold text-center">4. Dapatkan QR<br>konfirmasi via WA</p>
          </div>
        </div>
  
        <!-- Keterangan di bawah -->
        <p class="text-lg text-gray-700 max-w-2xl mx-auto">
          Tunjukkan QR code saat mengambil pesanan di lokasi distribusi kami, seperti koperasi sekolah atau stasiun.
        </p>
      </div>
    </section>
  
    <section class="py-20 bg-white" id="produk">
      <div class="max-w-7xl mx-auto px-4">
        <!-- Judul -->
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 text-center mb-12">Produk Favorit</h2>
  
        <!-- Grid Menu -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-6">
          @forelse($featuredMenus as $menu)
          <div class="bg-white rounded-lg shadow p-4">
            @if($menu->photo && $menu->photo !== 'default.jpg' && file_exists(public_path('storage/' . $menu->photo)))
              <img src="{{ asset('storage/' . $menu->photo) }}" alt="{{ $menu->name }}" class="w-full h-40 object-cover rounded mb-3">
            @else
              <div class="w-full h-40 bg-gray-200 rounded mb-3 flex items-center justify-center">
                <span class="text-gray-500 text-sm">Foto Produk</span>
              </div>
            @endif
            <h3 class="font-semibold text-gray-900 mb-1">{{ $menu->name }}</h3>
            <p class="text-sm text-gray-600 mb-1">{{ $menu->description }}</p>
            <div class="flex justify-between items-center">
              <span class="text-sm font-semibold text-primary-500">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
              @if($menu->category)
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $menu->category->name }}</span>
              @endif
            </div>
          </div>
          @empty
          <!-- Fallback jika tidak ada data -->
          <div class="col-span-full text-center py-8">
            <p class="text-gray-500">Belum ada produk tersedia</p>
          </div>
          @endforelse
        </div>
  
        <!-- Link ke Semua Menu -->
        <div class="text-center">
          <a href="#menu-lainnya" class="text-primary-500 font-medium hover:underline">Lihat Menu Lainnya..</a>
        </div>
      </div>
    </section>
  </main>

  <footer class="bg-neutral-50 pt-16 pb-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
      <!-- Kiri: Logo & Kontak -->
      <div class="space-y-4">
        <img src="{{ asset('images/logo.png') }}" alt="Calsfine Logo" class="h-10">
        <div class="text-sm text-gray-800 space-y-2">
          <p><strong>Email</strong><br>calsfinebyami@gmail.com</p>
          <p><strong>Phone Number</strong><br>0812-8747-8793</p>
        </div>
      </div>

      <!-- Tengah: CTA -->
      <div class="flex justify-center">
        <a href="#pesan" class="border border-primary-500 text-primary-500 font-medium px-6 py-2 rounded hover:bg-primary-500 hover:text-white transition">
          Pesan Sekarang
        </a>
      </div>

      <!-- Kanan: Navigasi -->
      <div class="text-sm text-gray-800 text-right space-y-2">
        <p><a href="#tentang" class="hover:underline">Tentang Kami</a></p>
        <p><a href="#produk" class="hover:underline">Produk</a></p>
        <p><a href="#cara-pesan" class="hover:underline">Cara pesan</a></p>
      </div>
    </div>
  </footer>

  <!-- Bar Copyright -->
  <div class="bg-primary-800 py-4">
    <p class="text-center text-xs text-white">© 2025 All rights reserved</p>
  </div>
</body>
</html>