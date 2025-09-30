<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HubLink — Jual & Bagikan Produk Digital Anda</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Inter',sans-serif}</style>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#00c499',
            'primary-dark': '#00a882',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-50">

<!-- Navbar -->
<nav class="bg-white shadow fixed w-full top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <h1 class="text-2xl font-bold text-primary">HubLink</h1>
      <div class="hidden md:flex items-center space-x-8">
        <a href="#features" class="text-gray-700 hover:text-primary">Fitur</a>
        <a href="#how-it-works" class="text-gray-700 hover:text-primary">Cara Kerja</a>
      </div>
      <div class="flex items-center space-x-4">
        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary">Masuk</a>
        <a href="{{ route('register') }}" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-full transition font-medium">Daftar Gratis</a>
      </div>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="pt-28 pb-20 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <div>
      <h2 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
        Satu Link untuk <span class="text-primary">Kreator & Usaha Digital</span>
      </h2>
      <p class="text-lg md:text-xl text-gray-600 mb-8">
        Hubungkan audiens Anda ke toko digital, portofolio, produk digital, dan semua kanal dalam satu halaman.
      </p>
      <div class="flex flex-col sm:flex-row gap-4">
        <a href="#how-it-works" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full transition font-semibold text-lg shadow-lg">Mulai Gratis</a>
        <a href="#features" class="border-2 border-primary text-primary hover:bg-primary hover:text-white px-6 py-3 rounded-full transition font-semibold text-lg">Lihat Fitur</a>
      </div>
    </div>
    <div class="relative">
      <div class="bg-gradient-to-br from-primary to-primary-dark rounded-3xl  shadow-2xl transform hover:scale-105 transition duration-300">
          <img src="{{ asset('vlogger.jpg') }}" alt="Preview Profil" 
           class="w-full aspect-video object-cover">
      </div>
    </div>
  </div>
</section>

<!-- Fitur -->
<section id="features" class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Utama HubLink</h2>
      <p class="text-lg md:text-xl text-gray-600">Alat lengkap untuk kreator dan usaha digital</p>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
      <div class="text-center p-6 md:p-8 rounded-2xl hover:shadow-lg transition">
        <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path></svg>
        </div>
        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Link & Tombol Custom</h3>
        <p class="text-gray-600">Buat tombol “Beli”, “Download”, “Kontak”, atau link apa pun</p>
      </div>
      <div class="text-center p-6 md:p-8 rounded-2xl hover:shadow-lg transition">
        <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zm0 6v6"></path></svg>
        </div>
        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Toko Digital & Pembayaran</h3>
        <p class="text-gray-600">Jual produk digital, terima pembayaran, dan terbitkan link otomatis</p>
      </div>
      <div class="text-center p-6 md:p-8 rounded-2xl hover:shadow-lg transition">
        <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17v-4a4 4 0 00-4-4H5M13 7h6a2 2 0 012 2v10a2 2 0 01-2 2h-6a4 4 0 00-4-4v-4"></path></svg>
        </div>
        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Analitik & Statistik</h3>
        <p class="text-gray-600">Pantau klik, penjualan, lokasi audiens, dan tren link</p>
      </div>
    </div>
  </div>
</section>

<!-- Cara Kerja -->
<section id="how-it-works" class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Cara Kerja HubLink</h2>
      <p class="text-lg md:text-xl text-gray-600 mb-8">3 langkah sederhana</p>
    </div>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
      <!-- Step 1 -->
      <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg text-center flex flex-col items-center">
        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center text-lg font-bold mb-4">1</div>
        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Daftar & Buat Profil</h3>
        <p class="text-gray-600">Isi info tentang brand, nama, logo, dan deskripsi singkat</p>
      </div>
      <!-- Step 2 -->
      <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg text-center flex flex-col items-center">
        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center text-lg font-bold mb-4">2</div>
        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Atur Toko & Link</h3>
        <p class="text-gray-600">Tambahkan produk digital, tombol beli, dan link sosial media</p>
      </div>
      <!-- Step 3 -->
      <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg text-center flex flex-col items-center">
        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center text-lg font-bold mb-4">3</div>
        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Bagikan & Jual</h3>
        <p class="text-gray-600">Sebarkan link HubLink Anda dan biarkan pembeli tiba langsung ke tombol beli</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA Akhir -->
<section class="py-16 bg-gradient-to-br from-primary to-primary-dark">
  <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap Memulai & Jual Produk Anda?</h2>
    <p class="text-lg md:text-xl text-white/90 mb-8">Gabung dengan kreator dan pelaku usaha digital yang sudah sukses menggunakan HubLink.</p>
    <a href="{{ route('register') }}" class="inline-block bg-white hover:bg-gray-100 text-primary px-8 py-3 rounded-full font-bold text-lg shadow-xl transition">Buat HubLink & Mulai Jual</a>
  </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-4 gap-8">
      <div>
        <h3 class="text-xl font-bold text-primary mb-4">HubLink</h3>
        <p class="text-gray-400">Platform link & toko digital untuk kreator & usaha.</p>
      </div>
      <div>
        <h4 class="font-bold mb-4">Produk</h4>
        <ul class="space-y-2 text-gray-400">
          <li><a href="#features" class="hover:text-primary">Fitur</a></li>
          <li><a href="#how-it-works" class="hover:text-primary">Cara Kerja</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-4">Perusahaan</h4>
        <ul class="space-y-2 text-gray-400">
          <li><a href="#" class="hover:text-primary">Tentang Kami</a></li>
          <li><a href="#" class="hover:text-primary">Blog</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-4">Bantuan</h4>
        <ul class="space-y-2 text-gray-400">
          <li><a href="#" class="hover:text-primary">FAQ</a></li>
          <li><a href="#" class="hover:text-primary">Kontak</a></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
      <p>&copy; {{ date('Y') }} HubLink. Hak Cipta Dilindungi.</p>
    </div>
  </div>
</footer>

<script>
  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach(anchor=>{
    anchor.addEventListener('click',function(e){
      e.preventDefault();
      const target=document.querySelector(this.getAttribute('href'));
      if(target){target.scrollIntoView({behavior:'smooth',block:'start'});}
    });
  });
</script>
</body>
</html>
