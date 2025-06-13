<?php
require_once('../../db/config.php');
require_once('../auth.php');

if (!isset($_GET["id"])) {
  echo "<script>window.history.back()</script>";
  exit();
}

// mendapatkan data awal user
$user_id = $_GET["id"] ?? "";
$user_query = "SELECT * FROM users WHERE username = ?";
$user_stmt = $db->prepare($user_query);
$user_stmt->bind_param("s", $user_id);
$user_stmt->execute();

$user_data = $user_stmt->get_result()->fetch_assoc();

if (!$user_data && !$_SERVER["REQUEST_METHOD"] == "POST") {
  echo "<script>window.location.href='index.php';</script>";
  exit();
}

// mendapatkan tipe-tipe darah
$blood_types = $db->query("SELECT * FROM blood_types")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // data pribadi
  $fullname = $_POST["fullname"];
  $age = $_POST["age"];
  $gender = $_POST["gender"];
  $birth_date = $_POST["birthdate"];

  // kontak
  $phone_number = $_POST["phone"];
  $email = $_POST["email"];
  $address = $_POST["address"];

  // akun
  $username = $_POST["username"];

  // informasi donor darah
  $blood_type = $_POST["bloodType"];
  $donation_history = $_POST["donorHistory"];
  $last_donation = $_POST["lastDonation"] ?? NULL;

  // insert data ke database
  $query = "UPDATE users
    SET username = ?, email = ?, fullname = ?, age = ?, phone = ?, address = ?, blood_type_id = ?, birth_date = ?, gender = ?, donation_history = ?
    WHERE username = ?";

  $stmt = $db->prepare($query);
  $stmt->bind_param(
    "sssississss",
    $username,
    $email,
    $fullname,
    $age,
    $phone_number,
    $address,
    $blood_type,
    $birth_date,
    $gender,
    $donation_history,
    $user_data["username"]
  );

  $result = $stmt->execute();

  if ($last_donation) {
    $last_donation_query = "UPDATE users SET last_donation = ? WHERE username = ?";
    $last_donation_stmt = $db->prepare($last_donation_query);
    $last_donation_stmt->bind_param("ss", $last_donation, $username);
    $last_donation_stmt->execute();
  }

  if ($result) {
    echo "<script>alert('Edit data berhasil!.'); window.location.href='index.php';</script>";
  } else {
    echo "<script>alert('Terjadi kesalahan saat edit data.'); window.history.back();</script>";
  }

  // tutup koneksi
  $user_username_stmt->close();
  $user_email_stmt->close();
  $admin_username_stmt->close();
  $admin_email_stmt->close();
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Pendonor Darah</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .password-toggle {
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }

    .password-strength {
      height: 4px;
      transition: all 0.3s;
    }
  </style>
</head>

<body class="bg-gray-100 font-['Poppins'] min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-4xl">
    <!-- Header -->
    <div class="bg-blood text-white p-6 flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold flex items-center">
          <i class="fas fa-user-plus mr-3"></i>
          Edit Data Pendonor
        </h1>
        <p class="text-blood-light mt-1">Masukkan informasi pendonor darah baru</p>
      </div>
      <button onclick="window.history.back()" class="text-white hover:text-blood-light">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>

    <!-- Registration Form -->
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
      <form id="donorForm" method="POST">
        <!-- Data Pribadi Section -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Data Pribadi</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Lengkap -->
            <div>
              <label for="fullname" class="block text-gray-700 text-sm font-medium mb-2">Nama Lengkap <span class="text-red-600">*</span></label>
              <input type="text" id="fullname" name="fullname" value="<?= $user_data["fullname"] ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Masukkan nama lengkap">
            </div>

            <!-- Umur -->
            <div>
              <label for="age" class="block text-gray-700 text-sm font-medium mb-2">Umur <span class="text-red-600">*</span></label>
              <input type="number" id="age" name="age" min="17" max="65" required value="<?= $user_data["age"] ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Minimal 17 tahun">
            </div>

            <!-- Jenis Kelamin -->
            <div>
              <label class="block text-gray-700 text-sm font-medium mb-2">Jenis Kelamin <span class="text-red-600">*</span></label>
              <div class="flex space-x-4">
                <label class="inline-flex items-center">
                  <input type="radio" name="gender" value="male" required <?= $user_data["gender"] === "male" ? "checked" : ""  ?>
                    class="text-red-600 focus:ring-red-500">
                  <span class="ml-2">Laki-laki</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="gender" value="female" <?= $user_data["gender"] === "female" ? "checked" : ""  ?>
                    class="text-red-600 focus:ring-red-500">
                  <span class="ml-2">Perempuan</span>
                </label>
              </div>
            </div>

            <!-- Tanggal Lahir -->
            <div>
              <label for="birthdate" class="block text-gray-700 text-sm font-medium mb-2">Tanggal Lahir <span class="text-red-600">*</span></label>
              <input type="date" id="birthdate" name="birthdate" value="<?= $user_data["birth_date"] ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
          </div>
        </div>

        <!-- Kontak Section -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Kontak</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nomor Telepon -->
            <div>
              <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Nomor Telepon <span class="text-red-600">*</span></label>
              <input type="tel" id="phone" name="phone" value="<?= $user_data["phone"] ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Contoh: 081234567890" pattern="[0-9]{10,13}">
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email <span class="text-red-600">*</span></label>
              <input type="email" id="email" name="email" value="<?= $user_data["email"] ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Contoh: anda@email.com">
            </div>

            <!-- Alamat -->
            <div class="md:col-span-2">
              <label for="address" class="block text-gray-700 text-sm font-medium mb-2">Alamat Lengkap <span class="text-red-600">*</span></label>
              <textarea id="address" name="address" rows="3" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Jl. Contoh No. 123, Kota"><?= $user_data["address"] ?></textarea>
            </div>
          </div>
        </div>

        <!-- Akun Section -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Akun</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Username -->
            <div class="col-span-2">
              <label for="username" class="block text-gray-700 text-sm font-medium mb-2">Username <span class="text-red-600">*</span></label>
              <input type="text" id="username" name="username" required minlength="5" value="<?= $user_data["username"] ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Minimal 5 karakter">
              <p class="text-xs text-gray-500 mt-1">Gunakan huruf, angka, atau underscore</p>
            </div>
          </div>
        </div>

        <!-- Informasi Donor Darah Section -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Donor Darah</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Golongan Darah -->
            <div>
              <label for="bloodType" class="block text-gray-700 text-sm font-medium mb-2">Golongan Darah <span class="text-red-600">*</span></label>
              <select id="bloodType" name="bloodType" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <option value="" disabled>Pilih golongan darah</option>
                <?php foreach ($blood_types as $type): ?>
                  <option value="<?= $type["id"] ?>" <?= $user_data["blood_type_id"] === $type["id"] ? "selected" : ""  ?> class="uppercase"><?= $type["type"] ?><?= $type["rhesus"] ?></option>
                <?php endforeach ?>
              </select>
            </div>

            <!-- Riwayat Donor -->
            <div>
              <label class="block text-gray-700 text-sm font-medium mb-2">Pernah Donor Sebelumnya?</label>
              <div class="flex space-x-4">
                <label class="inline-flex items-center">
                  <input type="radio" name="donorHistory" value="y" <?= $user_data["donation_history"] === "y" ? "checked" : "" ?>
                    class="text-red-600 focus:ring-red-500" id="donorYes">
                  <span class="ml-2">Ya</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="donorHistory" value="n" <?= $user_data["donation_history"] === "n" ? "checked" : "" ?>
                    class="text-red-600 focus:ring-red-500" id="donorNo">
                  <span class="ml-2">Tidak</span>
                </label>
              </div>
            </div>

            <!-- Tanggal Terakhir Donor (conditional) -->
            <div id="lastDonationContainer" class="<?= $user_data["donation_history"] === "n" ? "hidden" : "" ?> md:col-span-2">
              <label for="lastDonation" class="block text-gray-700 text-sm font-medium mb-2">Tanggal Terakhir Donor</label>
              <input type="date" id="lastDonation" name="lastDonation" value="<?= $user_data["last_donation"] ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4 pt-4 border-t">
          <button type="button" onclick="window.history.back()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200">
            Batal
          </button>
          <button type="submit" class="px-4 py-2 bg-blood hover:bg-blood-dark text-white rounded-lg transition duration-20">
            <i class="fas fa-save mr-2"></i>Edit Data
          </button>
        </div>
      </form>
    </div>
  </div>

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

    // Toggle last donation date field
    document.getElementById('donorYes').addEventListener('change', function() {
      document.getElementById('lastDonationContainer').classList.toggle('hidden', !this.checked);
    });

    document.getElementById('donorNo').addEventListener('change', function() {
      document.getElementById('lastDonationContainer').classList.toggle('hidden', this.checked);
    });

    // Form validation
    document.getElementById('donorForm').addEventListener('submit', function(e) {
      e.preventDefault();

      // Validate blood type
      const bloodType = document.getElementById('bloodType');
      if (bloodType.value === '') {
        alert('Silakan pilih golongan darah');
        bloodType.focus();
        return false;
      }

      // Validate age
      const age = document.getElementById('age');
      if (age.value < 17) {
        alert('Usia minimal untuk donor darah adalah 17 tahun');
        age.focus();
        return false;
      }

      // If all validations pass
      const form = document.getElementById('donorForm')
      form.method = 'post';
      form.action = 'edit.php?id=<?= $user_data["username"] ?>';
      form.submit();

      // Reset UI
      document.getElementById('lastDonationContainer').classList.add('hidden');
      document.querySelectorAll('.password-strength').forEach(el => {
        el.classList.add('bg-gray-200');
        el.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
      });
      document.getElementById('password-strength-text').textContent = '';
    });
  </script>
</body>

</html>