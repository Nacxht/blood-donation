<?php
require_once('db/config.php');
require_once("auth.php");

if (isset($_SESSION["role"])) {
  header("Location: admin/index.php");
  exit;
}
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
      animation: pulse 1s infinite;
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

        <!-- Desktop Menu -->
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-4">
            <a href="index.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Beranda</a>
            <a href="artikel.php" class="text-red-500 font-medium px-3 py-2 rounded-md transition">Artikel</a>
            <a href="donasi.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Donor</a>

            <?php if (isset($_SESSION["username"])): ?>
              <a href="users/profil.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Profil</a>
            <?php else: ?>
              <a href="auth/login.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Login</a>
              <a href="auth/registrasi.php" class="text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Register</a>
            <?php endif ?>
          </div>
        </div>

        <!-- Hamburger Button -->
        <div class="md:hidden">
          <button id="menu-toggle" class="focus:outline-none">
            <i class="fas fa-bars text-gray-600 text-2xl"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
      <div class="px-4 pt-4 pb-4 space-y-2">
        <a href="index.php" class="block text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Beranda</a>
        <a href="artikel.php" class="block text-red-500 font-medium px-3 py-2 rounded-md transition">Artikel</a>
        <a href="donasi.php" class="block text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Donor</a>

        <?php if (isset($_SESSION["username"])): ?>
          <a href="users/profil.php" class="block text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Profil</a>
        <?php else: ?>
          <a href="auth/login.php" class="block text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Login</a>
          <a href="auth/registrasi.php" class="block text-gray-600 hover:text-red-500 px-3 py-2 rounded-md transition">Register</a>
        <?php endif ?>
      </div>
    </div>
  </nav>

  <!-- Header -->
  <section class="gradient-bg text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Artikel & Informasi</h1>
      <p class="text-xl opacity-90">Pelajari lebih lanjut tentang donor darah dan kesehatan</p>
    </div>
  </section>

  <section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-gray-800 mb-8">Artikel Terbaru</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <?php
        $sql = "SELECT * FROM education_articles ORDER BY created_at DESC";
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
        ?>
            <article class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
              <img src="<?= htmlspecialchars($row['thumbnail']) ?>" alt="Thumbnail" class="w-full h-48 object-cover">
              <div class="p-6">
                <div class="flex items-center mb-3">
                  <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-medium">Artikel</span>
                  <span class="text-gray-400 ml-3 text-sm"><?= date("d M Y", strtotime($row['created_at'])) ?></span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3"><?= htmlspecialchars($row['title']) ?></h3>
                <p class="text-gray-600 mb-4">
                  <?= substr(strip_tags($row['content']), 0, 100) ?>...
                </p>
                <a href="#" class="text-red-500 font-semibold hover:text-red-600 transition">Baca Selengkapnya</a>
              </div>
            </article>
        <?php
          }
        } else {
          echo "<p class='text-center text-gray-500 col-span-3'>Belum ada artikel.</p>";
        }
        ?>

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
            <li><a href="donasi.php" class="hover:text-white transition">Cari Donor</a></li>
            <li><a href="auth/registrasi.php" class="hover:text-white transition">Daftar Donor</a></li>
            <li><a href="#" class="hover:text-white transition">Darurat</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Informasi</h3>
          <ul class="space-y-2 text-gray-400">
            <li><a href="artikel.php" class="hover:text-white transition">Artikel</a></li>
            <li><a href="#" class="hover:text-white transition">FAQ</a></li>
            <li><a href="#" class="hover:text-white transition">Bantuan</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Kontak</h3>
          <ul class="space-y-2 text-gray-400">
            <li><i class="fas fa-phone mr-2"></i> (021) 555-0123</li>
            <li><i class="fas fa-envelope mr-2"></i> info@bloodcare.id</li>
            <li><i class="fas fa-map-marker-alt mr-2"></i> Bangkalan, Indonesia</li>
          </ul>
        </div>
      </div>
      <hr class="border-gray-700 my-8">
      <div class="text-center text-gray-400">
        <p>&copy; 2025 BloodCare. Semua hak dilindungi.</p>
      </div>
    </div>
  </footer>
  <!-- JavaScript -->
  <script>
    const btn = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  </script>
</body>

</html>