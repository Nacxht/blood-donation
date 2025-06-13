<?php
require_once("../../db/config.php");
require_once("../auth.php");

$article_query = "SELECT * FROM education_articles";
$articles = $db->query($article_query)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Artikel Donor Darah</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            blood: {
              DEFAULT: '#cc0000',
              light: '#ffebee',
              dark: '#b30000',
              darker: '#990000'
            }
          }
        }
      }
    }
  </script>
</head>

<body class="font-['Poppins'] bg-gray-100">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-blood text-white">
      <div class="p-4 border-b border-blood-dark">
        <h1 class="text-xl font-bold">Admin Donor Darah</h1>
        <p class="text-sm text-blood-light">Sistem Manajemen Pendonor</p>
      </div>
      <nav class="p-4">
        <ul class="space-y-2">
          <li>
            <a href="../index.php" class="flex items-center p-2 rounded hover:bg-blood-dark">
              <i class="fas fa-tachometer-alt mr-3"></i>
              Dashboard
            </a>
          </li>
          <li>
            <a href="../data_pendonor/index.php" class="flex items-center p-2 rounded hover:bg-blood-dark">
              <i class="fas fa-users mr-3"></i>
              Data Pendonor
            </a>
          </li>
          <li>
            <a href="../donasi_darah/index.php" class="flex items-center p-2 rounded hover:bg-blood-dark">
              <i class="fa-solid fa-droplet mr-3"></i>
              Donasi Darah
            </a>
          </li>
          <li>
            <a href="index.php" class="flex items-center p-2 rounded bg-blood-dark">
              <i class="fa-solid fa-newspaper mr-3"></i>
              Artikel Edukasi
            </a>
          </li>
          <li>
            <a href="../../auth/logout.php" class="flex items-center p-2 rounded hover:bg-blood-dark">
              <i class="fa-solid fa-right-from-bracket mr-3"></i>
              Logout
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
          <i class="fas fa-newspaper mr-3 text-blood"></i>
          Daftar Artikel Donor Darah
        </h1>
        <a href="tambah.php" class="px-4 py-2 bg-blood hover:bg-blood-dark text-white rounded-lg transition duration-200 shadow-md hover:shadow-lg">
          <i class="fas fa-plus mr-2"></i>Tambah Artikel
        </a>
      </div>

      <!-- Articles Table -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blood text-white">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Thumbnail</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Judul Artikel</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tanggal Dibuat</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Terakhir Diupdate</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php foreach ($articles as $article): ?>
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <img src="<?= $article["thumbnail"] ?>" alt="Thumbnail" class="h-12 w-20 object-cover rounded">
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?= $article["title"] ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= date("Y-m-d", strtotime($article["created_at"])) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= date("Y-m-d", strtotime($article["updated_at"])) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="edit.php?id=<?= $article["id"] ?>" class="text-blue-600 hover:text-blue-900 mr-3 transition-colors duration-200">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button <?= 'onclick="deleteUser(' . $article['id'] . ')"' ?> class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    function deleteUser(id) {
      const confirmation = confirm("Apakah anda yakin ingin menghapus data ini?");

      if (!confirmation) {
        return
      }

      const body = document.querySelector("body");

      const form = document.createElement("form");
      form.method = "post";
      form.action = "hapus.php";
      form.classList.add("hidden");

      const input = document.createElement("input");
      input.value = id;
      input.type = "number"
      input.name = "id";

      form.appendChild(input);
      body.appendChild(form);

      form.submit();
      return
    }
  </script>
</body>

</html>