<?php
require_once('../db/config.php');
require_once('../auth.php');

if (isset($_SESSION["role"])) {
  header("Location: admin/index.php");
  exit;
}

// Ambil data user dari database
$username = $_SESSION['username'];
$query = "SELECT u.*, bt.type, bt.rhesus FROM users u 
          JOIN blood_types bt ON u.blood_type_id = bt.id 
          WHERE u.username = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Format tanggal untuk tampilan
$birth_date = isset($user['username']) ? date('d F Y', strtotime($user['birth_date'])) : "username";
$last_donation = isset($user['last_donation']) ? date('d F Y', strtotime($user['last_donation'])) : 'Belum pernah donor';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Pendonor Darah</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    .profile-card {
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .blood-type {
      font-size: 2rem;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body class="bg-red-50 font-['Poppins'] min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-4xl">
    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Profil Pendonor Darah</h1>
      <p class="text-gray-600 mt-2">Informasi akun dan data donor Anda</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-xl profile-card p-6 md:p-8">
      <div class="flex flex-col md:flex-row gap-8">
        <!-- Left Column - Blood Type & Avatar -->
        <div class="w-full md:w-1/3 flex flex-col items-center">
          <div class="relative mb-6">
            <div class="w-32 h-32 rounded-full bg-red-100 flex items-center justify-center mb-4 overflow-hidden border-4 border-white shadow-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
              </svg>
            </div>
            <div class="text-center">
              <div class="blood-type text-red-600 font-bold mb-1"><?= $user['type'] . $user['rhesus'] ?></div>
              <div class="text-sm text-gray-500">Golongan Darah</div>
            </div>
          </div>

          <div class="w-full bg-gray-100 rounded-lg p-4">
            <h3 class="font-semibold text-gray-800 mb-2">Status Donor</h3>
            <?php
            $lastDonation = $user['last_donation'] ? strtotime($user['last_donation']) : 0;
            $nextDonation = strtotime('+3 months', $lastDonation);
            $canDonate = time() >= $nextDonation;
            ?>
            <div class="flex items-center mb-1">
              <div class="w-3 h-3 rounded-full mr-2 <?= $canDonate ? 'bg-green-500' : 'bg-yellow-500' ?>"></div>
              <span class="text-sm"><?= $canDonate ? 'Siap donor' : 'Belum bisa donor' ?></span>
            </div>
            <?php if (!$canDonate && $lastDonation > 0): ?>
              <div class="text-xs text-gray-600 mt-1">Bisa donor lagi pada <?= date('d F Y', $nextDonation) ?></div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Right Column - User Info -->
        <div class="w-full md:w-2/3">
          <!-- Personal Info Section -->
          <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pribadi</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Nama Lengkap</p>
                <p class="font-medium"><?= htmlspecialchars($user['fullname']) ?></p>
              </div>

              <div>
                <p class="text-sm text-gray-500">Jenis Kelamin</p>
                <p class="font-medium"><?= $user['gender'] == 'male' ? 'Laki-laki' : 'Perempuan' ?></p>
              </div>

              <div>
                <p class="text-sm text-gray-500">Tanggal Lahir</p>
                <p class="font-medium"><?= $birth_date ?></p>
              </div>

              <div>
                <p class="text-sm text-gray-500">Umur</p>
                <p class="font-medium"><?= $user['age'] ?> tahun</p>
              </div>
            </div>
          </div>

          <!-- Contact Info Section -->
          <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Kontak</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium"><?= htmlspecialchars($user['email']) ?></p>
              </div>

              <div>
                <p class="text-sm text-gray-500">Nomor Telepon</p>
                <p class="font-medium"><?= htmlspecialchars($user['phone']) ?></p>
              </div>

              <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Alamat</p>
                <p class="font-medium"><?= htmlspecialchars($user['address']) ?></p>
              </div>
            </div>
          </div>

          <!-- Donation Info Section -->
          <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Donor</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Riwayat Donor</p>
                <p class="font-medium"><?= $user['donation_history'] == 'y' ? 'Pernah donor' : 'Belum pernah donor' ?></p>
              </div>

              <div>
                <p class="text-sm text-gray-500">Terakhir Donor</p>
                <p class="font-medium"><?= $last_donation ?></p>
              </div>
            </div>
          </div>

          <!-- Account Info Section -->
          <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Akun</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Username</p>
                <p class="font-medium"><?= htmlspecialchars($user['username']) ?></p>
              </div>

              <div>
                <p class="text-sm text-gray-500">Tanggal Registrasi</p>
                <p class="font-medium"><?= date('d F Y', strtotime($user['created_at'])) ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row justify-between mt-8 border-t pt-6 gap-4">
        <a href="../donasi.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg shadow-md text-center">Kembali</a>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="edit.php" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow-md text-center">Edit Profil</a>
          <a href="../auth/logout.php" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-lg shadow-md text-center">Logout</a>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-xs text-gray-500">
      © 2025 Sistem Donor Darah. Seluruh hak dilindungi.
    </div>
  </div>
</body>

</html>