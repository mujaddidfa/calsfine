<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calsfine - Makan Sehat, Praktis, dan Lezat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen">
  <!-- Main Navigation -->
  <nav class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and Desktop Navigation -->
            <div class="flex items-center">
                <div class="flex items-center mr-8">
                    <a href="{{ route('home') }}" class="hover:opacity-80 transition">
                        <img src="{{ asset('images/logo.png') }}" alt="CalsFine Logo" class="h-8 w-auto mr-3">
                    </a>
                </div>
                
                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex space-x-6">
                    <a href="#beranda" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-50">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Beranda
                        </span>
                    </a>
                    
                    <a href="#tentang" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-50">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tentang
                        </span>
                    </a>
                    
                    <a href="#cara-pesan" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-50">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            Cara Pesan
                        </span>
                    </a>
                    
                    <a href="#menu" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-50">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Menu
                        </span>
                    </a>
                </nav>
            </div>
            
            <!-- Desktop Order Button -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('order') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 13"></path>
                    </svg>
                    Pesan Sekarang
                </a>
            </div>

            <!-- Mobile hamburger menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" type="button" class="text-gray-600 hover:text-primary-600 focus:outline-none focus:text-primary-600 p-2" aria-label="Menu">
                    <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200 bg-gray-50">
                <!-- Mobile Navigation Links -->
                <a href="#beranda" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Beranda
                    </span>
                </a>
                
                <a href="#tentang" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tentang
                    </span>
                </a>
                
                <a href="#cara-pesan" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Cara Pesan
                    </span>
                </a>
                
                <a href="#menu" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Menu
                    </span>
                </a>

                <!-- Mobile Order Button -->
                <div class="pt-4 border-t border-gray-300 mt-4">
                    <a href="{{ route('order') }}" class="w-full bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 13"></path>
                        </svg>
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
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
            <a href="{{ route('order') }}" class="bg-primary-500 text-white px-6 py-2 rounded hover:bg-primary-600 transition">
              Pesan Sekarang
            </a>
            <a href="#menu" class="border border-primary-500 text-primary-500 px-6 py-2 rounded hover:bg-primary-500 hover:text-white transition">
              Lihat Menu
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
  
    <section class="py-20 bg-white" id="menu">
      <div class="max-w-7xl mx-auto px-4">
        <!-- Judul -->
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 text-center mb-12">Menu Favorit</h2>

        <!-- Grid Menu -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-6">
          @forelse($featuredMenus as $menu)
          <div class="bg-white rounded-lg shadow p-4">
            @if($menu->image && $menu->image !== 'default.jpg' && file_exists(public_path('storage/' . $menu->image)))
              <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-40 object-cover rounded mb-3">
            @else
              <div class="w-full h-40 bg-gray-200 rounded mb-3 flex items-center justify-center">
                <span class="text-gray-500 text-sm">Foto Menu</span>
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
            <p class="text-gray-500">Belum ada menu tersedia</p>
          </div>
          @endforelse
        </div>
  
        <!-- Link ke Semua Menu -->
        @if($featuredMenus->count())
        <div class="text-center">
          <a href="{{ route('order') }}" class="text-primary-500 font-medium hover:underline">Lihat Menu Lainnya..</a>
        </div>
        @endif
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
        <a href="{{ route('order') }}" class="border border-primary-500 text-primary-500 font-medium px-6 py-2 rounded hover:bg-primary-500 hover:text-white transition">
          Pesan Sekarang
        </a>
      </div>

      <!-- Kanan: Navigasi -->
      <div class="text-sm text-gray-800 text-right space-y-2">
        <p><a href="#tentang" class="hover:underline">Tentang Kami</a></p>
        <p><a href="#menu" class="hover:underline">Menu</a></p>
        <p><a href="#cara-pesan" class="hover:underline">Cara Pesan</a></p>
      </div>
    </div>
  </footer>

  <!-- Bar Copyright -->
  <div class="bg-primary-800 py-4">
    <p class="text-center text-xs text-white">© 2025 All rights reserved</p>
  </div>

  <script>
    // Mobile menu toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        if (mobileMenuButton && mobileMenu && menuIcon && closeIcon) {
            mobileMenuButton.addEventListener('click', function() {
                const isMenuOpen = !mobileMenu.classList.contains('hidden');
                
                if (isMenuOpen) {
                    // Close menu
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                } else {
                    // Open menu
                    mobileMenu.classList.remove('hidden');
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                }
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInsideNav = event.target.closest('nav');
                const isMenuOpen = !mobileMenu.classList.contains('hidden');
                
                if (!isClickInsideNav && isMenuOpen) {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });

            // Close mobile menu when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) { // md breakpoint
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });

            // Smooth scroll for anchor links
            const navLinks = document.querySelectorAll('nav a[href^="#"]');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                        
                        // Close mobile menu if open
                        if (!mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('hidden');
                            menuIcon.classList.remove('hidden');
                            closeIcon.classList.add('hidden');
                        }
                    }
                });
            });
        }
    });
  </script>
</body>
</html>