<?php
require_once("../../db/config.php");
require_once("../auth.php");

if (!isset($_GET["id"])) {
  echo "window.history.back()";
  exit();
}

$donation_id = $_GET["id"];
$donation_query = "SELECT * FROM donation_requests WHERE id = ?";
$donation_stmt = $db->prepare($donation_query);
$donation_stmt->bind_param("s", $donation_id);
$donation_stmt->execute();

$donation = $donation_stmt->get_result()->fetch_assoc();

if (!$donation) {
  echo "<script>window.location.href='index.php';</script>";
  exit();
}

$blood_types = $db->query("SELECT * FROM blood_types")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $event_name = $_POST["eventName"];
  $location = $_POST["eventLocation"];
  $date = $_POST["eventDate"];
  $time = $_POST["eventTime"];
  $description = $_POST["eventDescription"];
  $blood_type = $_POST["bloodType"];
  $status = $_POST["eventStatus"];

  $query = "UPDATE donation_requests
  SET event_name = ?, location = ?, date = ?, time = ?, description = ?, blood_type_id = ?, status = ?
  WHERE id = ?";

  $stmt = $db->prepare($query);
  $stmt->bind_param(
    "sssssssi",
    $event_name,
    $location,
    $date,
    $time,
    $description,
    $blood_type,
    $status,
    $donation_id
  );

  if ($stmt->execute()) {
    echo "<script>alert('Edit data berhasil!.'); window.location.href='index.php';</script>";
  } else {
    echo "<script>alert('Terjadi kesalahan saat mengubah data.'); window.history.back();</script>";
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Donasi Darah</title>
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

<body class="bg-gray-100 font-['Poppins']">
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
            <a href="index.php" class="flex items-center p-2 rounded bg-blood-dark">
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
    <div class="flex-1 p-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
          <i class="fas fa-tint mr-3 blood-color"></i>
          Ubah Donasi Darah
        </h1>
        <a href="index.php" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200">
          <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
      </div>

      <!-- Add Donation Form -->
      <div class="bg-white rounded-xl shadow-lg p-6">
        <form id="addDonationForm" action="edit.php?id=<?= $donation["id"] ?>" method="POST">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Event Name -->
            <div class="md:col-span-2">
              <label for="eventName" class="block text-gray-700 text-sm font-medium mb-2">
                Nama Acara <span class="text-red-600">*</span>
              </label>
              <input type="text" id="eventName" name="eventName" value="<?= $donation["event_name"] ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Nama acara donor darah">
            </div>

            <!-- Location -->
            <div class="md:col-span-2">
              <label for="eventLocation" class="block text-gray-700 text-sm font-medium mb-2">
                Lokasi <span class="text-red-600">*</span>
              </label>
              <input type="text" id="eventLocation" name="eventLocation" value="<?= $donation["location"] ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Lokasi acara">
            </div>

            <!-- Date -->
            <div>
              <label for="eventDate" class="block text-gray-700 text-sm font-medium mb-2">
                Tanggal <span class="text-red-600">*</span>
              </label>
              <input type="date" id="eventDate" name="eventDate" value="<?= $donation["date"] ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <!-- Time -->
            <div>
              <label for="eventTime" class="block text-gray-700 text-sm font-medium mb-2">
                Waktu <span class="text-red-600">*</span>
              </label>
              <input type="time" id="eventTime" name="eventTime" value="<?= $donation["time"] ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
              <label for="eventDescription" class="block text-gray-700 text-sm font-medium mb-2">
                Deskripsi
              </label>
              <textarea id="eventDescription" name="eventDescription" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Deskripsi acara"><?= $donation["description"] ?></textarea>
            </div>

            <!-- Goldar -->
            <div>
              <label for="bloodType" class="block text-gray-700 text-sm font-medium mb-2">Golongan Darah <span class="text-red-600">*</span></label>
              <select id="bloodType" name="bloodType" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent uppercase">
                <option value="" disabled>Pilih golongan darah</option>
                <?php foreach ($blood_types as $type): ?>
                  <option value="<?= $type["id"] ?>" <?= $type["id"] == $donation["blood_type_id"] ? "selected" : "" ?> class="uppercase"><?= $type["type"] ?><?= $type["rhesus"] ?></option>
                <?php endforeach ?>
              </select>
            </div>

            <!-- Status -->
            <div>
              <label for="eventStatus" class="block text-gray-700 text-sm font-medium mb-2">
                Status <span class="text-red-600">*</span>
              </label>
              <select id="eventStatus" name="eventStatus" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <option value="active" <?= $donation["status"] === 'active' ? "selected" : "" ?>>Aktif</option>
                <option value="inactive<?= $donation["status"] === 'inactive' ? "selected" : "" ?>">Tidak Aktif</option>
              </select>
            </div>
          </div>

          <div class="flex justify-end space-x-4 pt-6 mt-6 border-t">
            <a href="index.php" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200">
              Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blood hover:blood-bg-dark text-white rounded-lg transition duration-200">
              <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // 
  </script>
</body>

</html>