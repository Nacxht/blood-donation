<?php
require_once("../db/config.php");
require_once("auth.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  // cek ketersediaan akun di tabel users
  $user_query = "SELECT id, username, email, password FROM users WHERE email = ?";
  $user_stmt = $db->prepare($user_query);
  $user_stmt->bind_param("s", $email);
  $user_stmt->execute();
  $user_stmt->store_result();

  // cek ketersediaan akun di tabel admins
  $admin_query = "SELECT id, username, email, role, password FROM admins WHERE email = ?";
  $admin_stmt = $db->prepare($admin_query);
  $admin_stmt->bind_param("s", $email);
  $admin_stmt->execute();
  $admin_stmt->store_result();

  // jika akun tidak ditemukan
  if (!$user_stmt->num_rows() && !$admin_stmt->num_rows()) {
    echo "<script>alert('Akun tidak ditemukan!'); window.location.href='login.php';</script>";
    exit;
  }

  if ($user_stmt->num_rows()) {
    $user_stmt->bind_result($id, $username, $email, $hashed_password);
    $user_stmt->fetch();

    if (!password_verify($password, $hashed_password)) {
      echo "<script>alert('Password tidak cocok!'); window.location.href='login.php';</script>";
      exit;
    }

    $_SESSION["id"] = $id;
    $_SESSION["username"] = $username;
    $_SESSION["email"] = $email;

    echo "<script>alert('Login berhasil!'); window.location.href='../index.php';</script>";
  }

  if ($admin_stmt->num_rows()) {
    $admin_stmt->bind_result($id, $username, $email, $role, $hashed_password);
    $admin_stmt->fetch();

    if (!password_verify($password, $hashed_password)) {
      echo "<script>alert('Password tidak cocok!'); window.location.href='login.php';</script>";
      exit;
    }

    $_SESSION["id"] = $id;
    $_SESSION["username"] = $username;
    $_SESSION["email"] = $email;
    $_SESSION["role"] = $role;

    echo "<script>alert('Login berhasil!'); window.location.href='../admin/index.php';</script>";
  }

  $user_stmt->close();
  $admin_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Donor Darah</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    .password-toggle {
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }
  </style>
</head>

<body class="bg-red-50 font-['Poppins'] min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-md">
    <!-- Header -->
    <div class="text-center mb-8">
      <div class="flex justify-center mb-4">
        <div class="bg-white p-4 rounded-full shadow-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
          </svg>
        </div>
      </div>
      <h1 class="text-2xl font-bold text-gray-800">Login Pendonor Darah</h1>
      <p class="text-gray-600 mt-2">Masukkan email dan password Anda</p>
    </div>

    <!-- Login Form -->
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
      <form id="loginForm" action="login.php" method="post">
        <!-- Email Input -->
        <div class="mb-4">
          <label for="loginEmail" class="block text-gray-700 text-sm font-medium mb-2">Email <span class="text-red-600">*</span></label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <input type="email" id="loginEmail" name="email" required
              class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="Masukkan alamat email">
          </div>
        </div>

        <!-- Password Input -->
        <div class="mb-6">
          <label for="loginPassword" class="block text-gray-700 text-sm font-medium mb-2">Password <span class="text-red-600">*</span></label>
          <div class="relative">
            <input type="password" id="loginPassword" name="password" required minlength="8"
              class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="Masukkan password">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <span class="password-toggle absolute" onclick="toggleLoginPassword()">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </span>
          </div>
          <p class="text-xs text-gray-500 mt-1">Password minimal 8 karakter</p>
        </div>

        <!-- Login Button -->
        <button type="submit" name="submit-login" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 shadow-md">
          Masuk
        </button>
      </form>

      <!-- Register Link -->
      <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">Belum punya akun?
          <a href="registrasi.php" class="text-red-600 hover:text-red-800 font-medium">Daftar sekarang</a>
        </p>
      </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-xs text-gray-500">
      © 2025 Sistem Donor Darah. Seluruh hak dilindungi.
    </div>
  </div>

  <script>
    // Toggle password visibility
    function toggleLoginPassword() {
      const passwordField = document.getElementById('loginPassword');
      const toggleIcon = document.querySelector('#loginForm .password-toggle svg');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
      } else {
        passwordField.type = 'password';
        toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
      }
    }

    // Form validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();

      // Validate email format
      const email = document.getElementById('loginEmail');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email.value)) {
        alert('Masukkan alamat email yang valid');
        email.focus();
        return false;
      }

      // Validate password
      const password = document.getElementById('loginPassword');
      if (password.value.length < 8) {
        alert('Password harus minimal 8 karakter');
        password.focus();
        return false;
      }

      const form = document.getElementById('loginForm');

      form.method = 'post'
      form.action = 'login.php'
      form.submit();
    });
  </script>
</body>

</html>