<?php
require_once('../../db/config.php');
require_once('../auth.php');

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
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  // informasi donor darah
  $blood_type = $_POST["bloodType"];
  $donation_history = $_POST["donorHistory"];
  $last_donation = $_POST["lastDonation"];

  // pengecekan duplikasi username
  $user_username_query = "SELECT username FROM users WHERE username = ?";
  $user_username_stmt = $db->prepare($user_username_query);
  $user_username_stmt->bind_param("s", $username);
  $user_username_stmt->execute();
  $user_usernames = $user_username_stmt->get_result()->fetch_assoc();

  $admin_username_query = "SELECT username FROM admins WHERE username = ?";
  $admin_username_stmt = $db->prepare($admin_username_query);
  $admin_username_stmt->bind_param("s", $username);
  $admin_username_stmt->execute();
  $admins_usernames = $admin_username_stmt->get_result()->fetch_assoc();

  if (
    $user_usernames || $admins_usernames
  ) {
    echo "<script>alert('Username sudah ada!'); window.history.back();</script>";
    exit;
  }

  // pengecekan duplikasi email
  $user_email_query = "SELECT email FROM users WHERE email = ?";
  $user_email_stmt = $db->prepare($user_email_query);
  $user_email_stmt->bind_param("s", $email);
  $user_email_stmt->execute();
  $user_emails = $user_email_stmt->get_result()->fetch_assoc();

  $admin_email_query = "SELECT email FROM admins WHERE email = ?";
  $admin_email_stmt = $db->prepare($admin_email_query);
  $admin_email_stmt->bind_param("s", $email);
  $admin_email_stmt->execute();
  $admin_emails = $admin_email_stmt->get_result()->fetch_assoc();

  if (
    $user_emails || $admin_emails
  ) {
    echo "<script>alert('Email sudah ada!'); window.history.back();</script>";
    exit;
  }

  // insert data ke database
  $query = "INSERT INTO users
  (username, email, password, fullname, age, phone, address, blood_type_id, birth_date, gender, donation_history)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $db->prepare($query);
  $stmt->bind_param(
    "ssssississs",
    $username,
    $email,
    $password,
    $fullname,
    $age,
    $phone_number,
    $address,
    $blood_type,
    $birth_date,
    $gender,
    $donation_history,
  );

  $result = $stmt->execute();

  if (isset($last_donation)) {
    $last_donation_query = "UPDATE users SET last_donation = ? WHERE username = ?";
    $last_donation_stmt = $db->prepare($last_donation_query);
    $last_donation_stmt->bind_param("ss", $last_donation, $username);
    $last_donation_stmt->execute();
  }

  if ($result) {
    echo "<script>alert('Penambahan data berhasil!.'); window.location.href='index.php';</script>";
    exit;
  } else {
    echo "<script>alert('Terjadi kesalahan saat menyimpan data.'); window.history.back();</script>";
    exit;
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
          Tambah Pendonor Baru
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
              <input type="text" id="fullname" name="fullname" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Masukkan nama lengkap">
            </div>

            <!-- Umur -->
            <div>
              <label for="age" class="block text-gray-700 text-sm font-medium mb-2">Umur <span class="text-red-600">*</span></label>
              <input type="number" id="age" name="age" min="17" max="65" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Minimal 17 tahun">
            </div>

            <!-- Jenis Kelamin -->
            <div>
              <label class="block text-gray-700 text-sm font-medium mb-2">Jenis Kelamin <span class="text-red-600">*</span></label>
              <div class="flex space-x-4">
                <label class="inline-flex items-center">
                  <input type="radio" name="gender" value="male" required
                    class="text-red-600 focus:ring-red-500">
                  <span class="ml-2">Laki-laki</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="gender" value="female"
                    class="text-red-600 focus:ring-red-500">
                  <span class="ml-2">Perempuan</span>
                </label>
              </div>
            </div>

            <!-- Tanggal Lahir -->
            <div>
              <label for="birthdate" class="block text-gray-700 text-sm font-medium mb-2">Tanggal Lahir <span class="text-red-600">*</span></label>
              <input type="date" id="birthdate" name="birthdate" required
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
              <input type="tel" id="phone" name="phone" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Contoh: 081234567890" pattern="[0-9]{10,13}">
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email <span class="text-red-600">*</span></label>
              <input type="email" id="email" name="email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Contoh: anda@email.com">
            </div>

            <!-- Alamat -->
            <div class="md:col-span-2">
              <label for="address" class="block text-gray-700 text-sm font-medium mb-2">Alamat Lengkap <span class="text-red-600">*</span></label>
              <textarea id="address" name="address" rows="3" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Jl. Contoh No. 123, Kota"></textarea>
            </div>
          </div>
        </div>

        <!-- Akun Section -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Akun</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Username -->
            <div>
              <label for="username" class="block text-gray-700 text-sm font-medium mb-2">Username <span class="text-red-600">*</span></label>
              <input type="text" id="username" name="username" required minlength="5"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Minimal 5 karakter">
              <p class="text-xs text-gray-500 mt-1">Gunakan huruf, angka, atau underscore</p>
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password <span class="text-red-600">*</span></label>
              <div class="relative">
                <input type="password" id="password" name="password" required minlength="8"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent pr-10"
                  placeholder="Minimal 8 karakter">
                <span class="password-toggle absolute" onclick="togglePassword()">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </span>
              </div>
              <div class="mt-1">
                <div class="flex space-x-1">
                  <div id="strength-1" class="password-strength w-1/4 bg-gray-200 rounded"></div>
                  <div id="strength-2" class="password-strength w-1/4 bg-gray-200 rounded"></div>
                  <div id="strength-3" class="password-strength w-1/4 bg-gray-200 rounded"></div>
                  <div id="strength-4" class="password-strength w-1/4 bg-gray-200 rounded"></div>
                </div>
                <p id="password-strength-text" class="text-xs mt-1"></p>
              </div>
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
                <option value="" disabled selected>Pilih golongan darah</option>
                <?php foreach ($blood_types as $type): ?>
                  <option value="<?= $type["id"] ?>" class="uppercase"><?= $type["type"] ?><?= $type["rhesus"] ?></option>
                <?php endforeach ?>
              </select>
            </div>

            <!-- Riwayat Donor -->
            <div>
              <label class="block text-gray-700 text-sm font-medium mb-2">Pernah Donor Sebelumnya?</label>
              <div class="flex space-x-4">
                <label class="inline-flex items-center">
                  <input type="radio" name="donorHistory" value="y"
                    class="text-red-600 focus:ring-red-500" id="donorYes">
                  <span class="ml-2">Ya</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="donorHistory" value="n" checked
                    class="text-red-600 focus:ring-red-500" id="donorNo">
                  <span class="ml-2">Tidak</span>
                </label>
              </div>
            </div>

            <!-- Tanggal Terakhir Donor (conditional) -->
            <div id="lastDonationContainer" class="hidden md:col-span-2">
              <label for="lastDonation" class="block text-gray-700 text-sm font-medium mb-2">Tanggal Terakhir Donor</label>
              <input type="date" id="lastDonation" name="lastDonation"
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
            <i class="fas fa-save mr-2"></i>Simpan Data
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

    // Toggle password visibility
    function togglePassword() {
      const passwordField = document.getElementById('password');
      const toggleIcon = document.querySelector('.password-toggle svg');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
      } else {
        passwordField.type = 'password';
        toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
      }
    }

    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
      const password = this.value;
      const strengthText = document.getElementById('password-strength-text');
      const strengthBars = [
        document.getElementById('strength-1'),
        document.getElementById('strength-2'),
        document.getElementById('strength-3'),
        document.getElementById('strength-4')
      ];

      let strength = 0;

      // Check length
      if (password.length >= 8) strength++;
      if (password.length >= 12) strength++;

      // Check complexity
      if (/[A-Z]/.test(password)) strength++;
      if (/[0-9]/.test(password)) strength++;
      if (/[^A-Za-z0-9]/.test(password)) strength++;

      // Cap at 4
      strength = Math.min(strength, 4);

      // Update UI
      strengthBars.forEach((bar, index) => {
        if (index < strength) {
          bar.classList.remove('bg-gray-200');
          if (strength <= 2) {
            bar.classList.add('bg-red-500');
          } else if (strength === 3) {
            bar.classList.add('bg-yellow-500');
          } else {
            bar.classList.add('bg-green-500');
          }
        } else {
          bar.classList.add('bg-gray-200');
          bar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
        }
      });

      // Update text
      if (password.length === 0) {
        strengthText.textContent = '';
      } else if (strength <= 1) {
        strengthText.textContent = 'Lemah';
        strengthText.className = 'text-xs mt-1 text-red-600';
      } else if (strength <= 2) {
        strengthText.textContent = 'Cukup';
        strengthText.className = 'text-xs mt-1 text-yellow-600';
      } else if (strength <= 3) {
        strengthText.textContent = 'Kuat';
        strengthText.className = 'text-xs mt-1 text-blue-600';
      } else {
        strengthText.textContent = 'Sangat Kuat';
        strengthText.className = 'text-xs mt-1 text-green-600';
      }
    });

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

      // Validate password
      const password = document.getElementById('password');
      if (password.value.length < 8) {
        alert('Password harus minimal 8 karakter');
        password.focus();
        return false;
      }

      // If all validations pass
      const form = document.getElementById('donorForm')
      form.method = 'post';
      form.action = 'tambah.php';
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