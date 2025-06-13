<?php
require_once("../db/config.php");
require_once("auth.php");

// mendapatkan semua data pendonor
$blood_donors_query = "SELECT
users.*, blood_types.type, blood_types.rhesus
FROM users
LEFT JOIN blood_types ON users.blood_type_id = blood_types.id";

$blood_donors = $db->query($blood_donors_query)->fetch_all(MYSQLI_ASSOC);

// fungsi untuk menghitung pendonor berdasarkan tipe darah dan rhesusnya
function get_donors_count_by_blood_rhesus(String $blood_type, String $rhesus, $db)
{
  $query = "SELECT
  blood_types.rhesus, COUNT(users.id) AS total
  from blood_types
  LEFT JOIN users ON blood_types.id = users.blood_type_id
  WHERE blood_types.type = ? AND blood_types.rhesus = ?
  GROUP BY blood_types.rhesus";

  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $blood_type, $rhesus);
  $stmt->execute();
  $result = $stmt->get_result();

  return $result->fetch_assoc();
}

// fungsi pembagi
function divisor(int | String $num1, int | String $num2)
{
  if (!$num1 || !$num2) {
    return 0;
  }

  return $num1 / $num2;
}

// goldar A
$total_a_rhesus_plus = get_donors_count_by_blood_rhesus("a", "+", $db)["total"];
$total_a_rhesus_minus = get_donors_count_by_blood_rhesus("a", "-", $db)["total"];
$total_a = $total_a_rhesus_plus + $total_a_rhesus_minus;

// goldar B
$total_b_rhesus_plus = get_donors_count_by_blood_rhesus("b", "+", $db)["total"];
$total_b_rhesus_minus = get_donors_count_by_blood_rhesus("b", "-", $db)["total"];
$total_b = $total_b_rhesus_plus + $total_b_rhesus_minus;

// goldar AB
$total_ab_rhesus_plus = get_donors_count_by_blood_rhesus("ab", "+", $db)["total"];
$total_ab_rhesus_minus = get_donors_count_by_blood_rhesus("ab", "-", $db)["total"];
$total_ab = $total_ab_rhesus_plus + $total_ab_rhesus_minus;

// goldar O
$total_o_rhesus_plus = get_donors_count_by_blood_rhesus("o", "+", $db)["total"];
$total_o_rhesus_minus = get_donors_count_by_blood_rhesus("o", "-", $db)["total"];
$total_o = $total_o_rhesus_plus + $total_o_rhesus_minus;
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Sistem Donor Darah</title>
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
            <a href="index.php" class="flex items-center p-2 rounded bg-blood-dark">
              <i class="fas fa-tachometer-alt mr-3"></i>
              Dashboard
            </a>
          </li>
          <li>
            <a href="data_pendonor/index.php" class="flex items-center p-2 rounded hover:bg-blood-dark">
              <i class="fas fa-users mr-3"></i>
              Data Pendonor
            </a>
          </li>
          <li>
            <a href="donasi_darah/index.php" class="flex items-center p-2 rounded hover:bg-blood-dark">
              <i class="fa-solid fa-droplet mr-3"></i>
              Donasi Darah
            </a>
          </li>
          <li>
            <a href="artikel/index.php" class="flex items-center p-2 rounded hover:bg-blood-dark">
              <i class="fa-solid fa-newspaper mr-3"></i>
              Artikel Edukasi
            </a>
          </li>
          <li>
            <a href="../auth/logout.php" class="flex items-center p-2 rounded hover:bg-blood-dark">
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
          <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
          <div class="flex items-center space-x-4">
            <div class="flex items-center">
              <i class="fa-solid fa-user-tie"></i>
              <span class="ml-2"><?= $_SESSION["username"] ?></span>
            </div>
          </div>
        </div>
      </header>

      <!-- Content -->
      <main class="p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="">
                <i class="fa-solid fa-users-line p-3 rounded-full bg-blood-light text-blood mr-4 aspect-square"></i>
              </div>
              <div>
                <p class="text-gray-500">Total Pendonor</p>
                <h3 class="text-2xl font-bold"><?= count($blood_donors) ?></h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Golongan Darah Section -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Distribusi Golongan Darah</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Golongan A -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
              <div class="bg-red-600 text-white p-4">
                <h3 class="text-xl font-bold">Golongan A</h3>
                <p class="text-red-100">Total Pendonor: <?= $total_a ?></p>
              </div>
              <div class="p-4">
                <div class="flex justify-between mb-2">
                  <span>A+</span>
                  <span class="font-medium">
                    <?= $total_a_rhesus_plus ?>
                    (<?= round(divisor($total_a_rhesus_plus, $total_a) * 100, 2) ?>%)
                  </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-red-600 h-2.5 rounded-full" style="width: <?= round(divisor($total_a_rhesus_plus, $total_a) * 100, 2) ?>%"></div>
                </div>
                <div class="flex justify-between mt-4 mb-2">
                  <span>A-</span>
                  <span class="font-medium">
                    <?= $total_a_rhesus_minus ?>
                    (<?= round(divisor($total_a_rhesus_minus, $total_a) * 100, 2) ?>%)
                  </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-red-400 h-2.5 rounded-full" style="width: <?= round(divisor($total_a_rhesus_minus, $total_a) * 100, 2) ?>%"></div>
                </div>
              </div>
            </div>

            <!-- Golongan B -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
              <div class="bg-blue-600 text-white p-4">
                <h3 class="text-xl font-bold">Golongan B</h3>
                <p class="text-blue-100">Total Pendonor: <?= $total_b ?></p>
              </div>
              <div class="p-4">
                <div class="flex justify-between mb-2">
                  <span>B+</span>
                  <span class="font-medium">
                    <?= $total_b_rhesus_plus ?>
                    (<?= round(divisor($total_b_rhesus_plus, $total_b) * 100, 2) ?>%)
                  </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?= round(divisor($total_b_rhesus_plus, $total_b) * 100, 2) ?>%"></div>
                </div>
                <div class="flex justify-between mt-4 mb-2">
                  <span>B-</span>
                  <span class="font-medium">
                    <?= $total_b_rhesus_minus ?>
                    (<?= round(divisor($total_b_rhesus_minus, $total_b) * 100, 2) ?>%)
                  </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-blue-400 h-2.5 rounded-full" style="width: <?= round(divisor($total_b_rhesus_minus, $total_b) * 100, 2) ?>%"></div>
                </div>
              </div>
            </div>

            <!-- Golongan AB -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
              <div class="bg-purple-600 text-white p-4">
                <h3 class="text-xl font-bold">Golongan AB</h3>
                <p class="text-purple-100">Total Pendonor: <?= $total_ab ?></p>
              </div>
              <div class="p-4">
                <div class="flex justify-between mb-2">
                  <span>AB+</span>
                  <span class="font-medium">
                    <?= $total_ab_rhesus_plus ?>
                    (<?= round(divisor($total_ab_rhesus_plus, $total_ab) * 100, 2) ?>%)
                  </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-purple-600 h-2.5 rounded-full" style="width: <?= round(divisor($total_ab_rhesus_plus, $total_ab) * 100, 2) ?>%"></div>
                </div>
                <div class="flex justify-between mt-4 mb-2">
                  <span>AB-</span>
                  <span class="font-medium">
                    <?= $total_ab_rhesus_minus ?>
                    (<?= round(divisor($total_ab_rhesus_minus, $total_ab) * 100, 2) ?>%)
                  </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-purple-400 h-2.5 rounded-full" style="width: <?= round(divisor($total_ab_rhesus_minus, $total_ab) * 100, 2) ?>%"></div>
                </div>
              </div>
            </div>

            <!-- Golongan O -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
              <div class="bg-green-600 text-white p-4">
                <h3 class="text-xl font-bold">Golongan O</h3>
                <p class="text-green-100">Total Pendonor: <?= $total_o ?></p>
              </div>
              <div class="p-4">
                <div class="flex justify-between mb-2">
                  <span>O+</span>
                  <span class="font-medium">
                    <?= $total_o_rhesus_plus ?>
                    (<?= round(divisor($total_o_rhesus_plus, $total_o) * 100, 2) ?> %)
                  </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-green-600 h-2.5 rounded-full" style="width: <?= round(divisor($total_o_rhesus_plus, $total_o) * 100, 2) ?>%"></div>
                </div>
                <div class="flex justify-between mt-4 mb-2">
                  <span>O-</span>
                  <span class="font-medium">
                    <?= $total_o_rhesus_minus ?>
                    (<?= round(divisor($total_o_rhesus_minus, $total_o) * 100, 2) ?> %)
                  </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-green-400 h-2.5 rounded-full" style="width: <?= round(divisor($total_o_rhesus_minus, $total_o) * 100, 2) ?>%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Data Pendonor Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">Data Pendonor</h2>
            <a href="data_pendonor/tambah.php" class="px-4 py-2 bg-blood hover:bg-blood-dark text-white rounded-lg">
              <i class="fas fa-plus mr-2"></i>Tambah Pendonor
            </a>
          </div>
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
                      <a href="data_pendonor/edit.php?id=<?= $donor['username'] ?>" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></a>
                      <button <?= 'onclick="deleteUser(' . $donor['id'] . ')"' ?> class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="px-6 py-4 border-t flex items-center justify-between">
            <!--  -->
          </div>
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
      form.action = "data_pendonor/hapus.php";
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