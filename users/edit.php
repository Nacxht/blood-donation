<?php
require_once('../db/config.php');
require_once('../auth.php');

if (isset($_SESSION["role"])) {
  header("Location: admin/index.php");
  exit;
}

$username = $_SESSION['username'];

// Ambil data user
$query = "SELECT u.*, bt.type, bt.rhesus FROM users u 
          JOIN blood_types bt ON u.blood_type_id = bt.id 
          WHERE u.username = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
  echo "<script>alert('Data tidak ditemukan'); window.location.href='../profil.php';</script>";
  exit;
}

// Ambil data blood types untuk pilihan select
$blood_types_result = $db->query("SELECT * FROM blood_types");
$blood_types = $blood_types_result->fetch_all(MYSQLI_ASSOC);

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = trim($_POST['fullname']);
  $age = intval($_POST['age']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);
  $gender = $_POST['gender'];
  $birth_date = $_POST['birth_date'];
  $blood_type_id = intval($_POST['blood_type']);
  $donation_history = $_POST['donation_history'];
  $last_donation = !empty($_POST['last_donation']) ? $_POST['last_donation'] : null;

  // Update data
  $update = $db->prepare("UPDATE users 
        SET fullname = ?, age = ?, phone = ?, address = ?, gender = ?, birth_date = ?, blood_type_id = ?, donation_history = ?, last_donation = ?
        WHERE username = ?");
  $update->bind_param(
    "sissssisss",
    $fullname,
    $age,
    $phone,
    $address,
    $gender,
    $birth_date,
    $blood_type_id,
    $donation_history,
    $last_donation,
    $username
  );

  if ($update->execute()) {
    echo "<script>alert('Profil berhasil diperbarui'); window.location.href='profil.php';</script>";
    exit;
  } else {
    echo "<script>alert('Gagal update profil');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profil</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-red-50 font-['Poppins'] min-h-screen flex items-center justify-center p-4">

  <div class="container mx-auto px-4 py-6">
    <div class="bg-white p-6 md:p-10 rounded-lg shadow-md max-w-3xl mx-auto">
      <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Edit Profil</h1>

      <form method="POST" class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label>Nama Lengkap</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required
              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400">
          </div>

          <div>
            <label>Umur</label>
            <input type="number" name="age" value="<?= $user['age'] ?>" min="17" max="65" required
              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400">
          </div>

          <div>
            <label>Jenis Kelamin</label>
            <div class="flex items-center gap-6">
              <label><input type="radio" name="gender" value="male" <?= $user['gender'] == 'male' ? 'checked' : '' ?>> Laki-laki</label>
              <label><input type="radio" name="gender" value="female" <?= $user['gender'] == 'female' ? 'checked' : '' ?>> Perempuan</label>
            </div>
          </div>

          <div>
            <label>Tanggal Lahir</label>
            <input type="date" name="birth_date" value="<?= $user['birth_date'] ?>" required
              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400">
          </div>

          <div>
            <label>Nomor Telepon</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required
              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400">
          </div>

          <div>
            <label>Alamat</label>
            <textarea name="address" rows="3" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400"><?= htmlspecialchars($user['address']) ?></textarea>
          </div>

          <div>
            <label>Golongan Darah</label>
            <select name="blood_type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400" required>
              <option value="">-- Pilih --</option>
              <?php foreach ($blood_types as $bt): ?>
                <option value="<?= $bt['id'] ?>" <?= $user['blood_type_id'] == $bt['id'] ? 'selected' : '' ?>>
                  <?= strtoupper($bt['type'] . $bt['rhesus']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label>Riwayat Donor</label>
            <div class="flex items-center gap-6">
              <label><input type="radio" name="donation_history" value="y" <?= $user['donation_history'] == 'y' ? 'checked' : '' ?>> Pernah</label>
              <label><input type="radio" name="donation_history" value="n" <?= $user['donation_history'] == 'n' ? 'checked' : '' ?>> Belum</label>
            </div>
          </div>

          <div class="md:col-span-2">
            <label>Terakhir Donor</label>
            <input type="date" name="last_donation" value="<?= $user['last_donation'] ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400">
            <small class="text-gray-500">Kosongkan jika belum pernah donor</small>
          </div>
        </div>

        <div class="flex justify-between pt-6 border-t">
          <a href="profil.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">Batal</a>
          <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">Simpan</button>
        </div>

      </form>
    </div>
  </div>

</body>

</html>