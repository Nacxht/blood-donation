<?php
require_once("auth.php");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Artikel - BloodCare</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-hover {
      transition: all 0.3s ease;
    }

    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .blood-drop {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.1);
      }
    }
  </style>
</head>

<body class="bg-gray-50">
  <!-- Navigation -->
  <nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center">
          <i class="fas fa-tint text-red-500 text-2xl blood-drop"></i>
          <span class="ml-2 text-xl font-bold text-gray-800">BloodCare</span>
        </div>
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-4">
            <a href="#" class="hover:text-red-500 font-medium px-3 py-2 rounded-md">Beranda</a>
            <a href="artikel.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Artikel</a>
            <a href="donasi.php" class="text-gray-600 text-red-500 px-3 py-2 rounded-md transition">Donor</a>

            <?php if (isset($_SESSION["username"])): ?>
              <a href="#" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Profile</a>
            <?php else: ?>
              <a href="auth/login.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Login</a>
              <a href="auth/registrasi.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Register</a>
            <?php endif ?>
          </div>
        </div>
        <button class="md:hidden">
          <i class="fas fa-bars text-gray-600"></i>
        </button>
      </div>
    </div>
  </nav>

  <!-- Header Section -->
  <section class="gradient-bg text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
          Artikel & Informasi
        </h1>
        <p class="text-xl opacity-90">
          Pelajari lebih lanjut tentang donor darah dan kesehatan
        </p>
      </div>
    </div>
  </section>

  <section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-gray-800 mb-8">Artikel Terbaru</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Article 2 -->
        <article class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
          <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Article" class="w-full h-48 object-cover">
          <div class="p-6">
            <div class="flex items-center mb-3">
              <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-medium">Donor Darah</span>
              <span class="text-gray-400 ml-3 text-sm">10 Juni 2025</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Mitos dan Fakta tentang Donor Darah</h3>
            <p class="text-gray-600 mb-4">Memecah mitos yang berkembang di masyarakat tentang donor darah dan memberikan informasi yang akurat.</p>
            <a href="#" class="text-red-500 font-semibold hover:text-red-600 transition">Baca Selengkapnya</a>
          </div>
        </article>

        <!-- Article 3 -->
        <article class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
          <img src="https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Article" class="w-full h-48 object-cover">
          <div class="p-6">
            <div class="flex items-center mb-3">
              <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-medium">Nutrisi</span>
              <span class="text-gray-400 ml-3 text-sm">8 Juni 2025</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Recovery Pasca Donor: Yang Harus Dikonsumsi</h3>
            <p class="text-gray-600 mb-4">Tips pemulihan cepat setelah donor darah dengan konsumsi makanan dan minuman yang tepat.</p>
            <a href="#" class="text-red-500 font-semibold hover:text-red-600 transition">Baca Selengkapnya</a>
          </div>
        </article>

        <!-- Article 4 -->
        <article class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
          <img src="https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Article" class="w-full h-48 object-cover">
          <div class="p-6">
            <div class="flex items-center mb-3">
              <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-medium">Donor Darah</span>
              <span class="text-gray-400 ml-3 text-sm">5 Juni 2025</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Seberapa Sering Boleh Donor Darah?</h3>
            <p class="text-gray-600 mb-4">Mengetahui interval yang aman antara donor darah untuk menjaga kesehatan donor dan penerima.</p>
            <a href="#" class="text-red-500 font-semibold hover:text-red-600 transition">Baca Selengkapnya</a>
          </div>
        </article>

        <!-- Article 5 -->
        <article class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
          <img src="https://images.unsplash.com/photo-1666214280557-f1b5022eb634?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Article" class="w-full h-48 object-cover">
          <div class="p-6">
            <div class="flex items-center mb-3">
              <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-sm font-medium">Tips Kesehatan</span>
              <span class="text-gray-400 ml-3 text-sm">3 Juni 2025</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Cara Mengatasi Rasa Takut Saat Donor Darah</h3>
            <p class="text-gray-600 mb-4">Tips mengatasi kecemasan dan rasa takut jarum saat akan melakukan donor darah pertama kali.</p>
            <a href="#" class="text-red-500 font-semibold hover:text-red-600 transition">Baca Selengkapnya</a>
          </div>
        </article>

        <!-- Article 6 -->
        <article class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
          <img src="https://images.unsplash.com/photo-1559757175-4d9e555645cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Article" class="w-full h-48 object-cover">
          <div class="p-6">
            <div class="flex items-center mb-3">
              <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-sm font-medium">Edukasi</span>
              <span class="text-gray-400 ml-3 text-sm">1 Juni 2025</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Golongan Darah: Kompatibilitas dan Keunikan</h3>
            <p class="text-gray-600 mb-4">Memahami sistem golongan darah ABO dan Rhesus serta kompatibilitasnya dalam transfusi darah.</p>
            <a href="#" class="text-red-500 font-semibold hover:text-red-600 transition">Baca Selengkapnya</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <div class="flex items-center mb-4">
            <i class="fas fa-tint text-red-500 text-2xl"></i>
            <span class="ml-2 text-xl font-bold">BloodCare</span>
          </div>
          <p class="text-gray-400">Platform donor darah terpercaya untuk menyelamatkan nyawa.</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Layanan</h3>
          <ul class="space-y-2 text-gray-400">
            <li><a href="#" class="hover:text-white transition">Cari Donor</a></li>
            <li><a href="#" class="hover:text-white transition">Daftar Donor</a></li>
            <li><a href="#" class="hover:text-white transition">Darurat</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Informasi</h3>
          <ul class="space-y-2 text-gray-400">
            <li><a href="#" class="hover:text-white transition">Artikel</a></li>
            <li><a href="#" class="hover:text-white transition">FAQ</a></li>
            <li><a href="#" class="hover:text-white transition">Bantuan</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Kontak</h3>
          <ul class="space-y-2 text-gray-400">
            <li><i class="fas fa-phone mr-2"></i> (021) 555-0123</li>
            <li><i class="fas fa-envelope mr-2"></i> info@bloodcare.id</li>
            <li><i class="fas fa-map-marker-alt mr-2"></i> Jakarta, Indonesia</li>
          </ul>
        </div>
      </div>
      <hr class="border-gray-700 my-8">
      <div class="text-center text-gray-400">
        <p>&copy; 2025 BloodCare. Semua hak dilindungi.</p>
      </div>
    </div>
  </footer>
</body>

</html>