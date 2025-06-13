<?php
require_once("../../db/config.php");
require_once("../auth.php");

// mendapatkan semua data pendonor
$blood_donors_query = "SELECT
users.*, blood_types.type, blood_types.rhesus
FROM users
LEFT JOIN blood_types ON users.blood_type_id = blood_types.id";

$blood_donors = $db->query($blood_donors_query)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pendonor - Admin Donor Darah</title>
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
              dark: '#b30000'
            }
          }
        }
      }
    }
  </script>
</head>

<body class="font-['Poppins'] bg-gray-100">
  <div class="flex h-screen">
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
            <a href="index.php" class="flex items-center p-2 rounded bg-blood-dark">
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
            <a href="../artikel/index.php" class="flex items-center p-2 rounded hover:bg-blood-dark">
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
    <div class="flex-1 overflow-auto">
      <!-- Header -->
      <header class="bg-white shadow-sm">
        <div class="flex justify-between items-center p-4">
          <h2 class="text-xl font-semibold text-gray-800">Data Pendonor</h2>
          <div class="flex items-center space-x-4">
            <div class="flex items-center">
              <i class="fa-solid fa-user-tie"></i>
              <span class="ml-2">Admin</span>
            </div>
          </div>
        </div>
      </header>

      <!-- Content -->
      <main class="p-6">
        <!-- Filter and Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
          <div class="flex gap-3 w-full md:w-auto">
            <a href="tambah.php" class="px-4 py-2 bg-blood hover:bg-blood-dark text-white rounded-lg w-full md:w-auto">
              <i class="fas fa-plus mr-2"></i>Tambah Pendonor
            </a>
          </div>
        </div>

        <!-- Donor Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gol. Darah</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usia</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JK</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Donor</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($blood_donors as $donor): ?>
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <i class="fa-solid fa-users-line"></i>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900"><?= $donor["fullname"] ?></div>
                          <div class="text-sm text-gray-500"><?= $donor["email"] ?></div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 uppercase"><?= $donor["type"] . $donor["rhesus"] ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $donor["age"] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize"><?= $donor["gender"] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $donor["last_donation"] ?? "-" ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <a href="edit.php?id=<?= $donor['username'] ?>" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></a>
                      <button <?= 'onclick="deleteUser(' . $donor['id'] . ')"' ?> class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="px-6 py-4 border-t flex flex-col md:flex-row items-center justify-between">
            <!--  -->
          </div>
        </div>
    </div>
    </main>
  </div>
  </div>
  <script src="https://kit.fontawesome.com/74f7762059.js" crossorigin="anonymous"></script>

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