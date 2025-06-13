<?php
require_once("../../db/config.php");
require_once("../auth.php");

if (!isset($_GET["id"])) {
  echo "<script>window.history.back();</script>";
}

// mendapatkan data artikel
$article_id = $_GET["id"];
$article_query = "SELECT * FROM education_articles WHERE id = ?";
$article_stmt = $db->prepare($article_query);
$article_stmt->bind_param("i", $article_id);
$article_stmt->execute();

$article = $article_stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST["title"];
  $thumbnail = $_POST["thumbnail"];
  $content = $_POST["content"];

  $query = "UPDATE education_articles
  SET title = ?, content = ?, thumbnail = ? WHERE id = ?";

  $stmt = $db->prepare($query);
  $stmt->bind_param(
    "sssi",
    $title,
    $content,
    $thumbnail,
    $article_id
  );

  $result = $stmt->execute();

  if ($result) {
    echo "<script>alert('Edit data berhasil!.'); window.location.href='index.php';</script>";
  } else {
    echo "<script>alert('Terjadi kesalahan saat menyimpan data.'); window.history.back();</script>";
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Artikel Donor Darah</title>
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
  <div class="container mx-auto p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-blood-darker">
        <i class="fas fa-plus-circle mr-3"></i>
        Tambah Artikel Baru
      </h1>
      <a href="index.php" class="px-4 py-2 border border-blood rounded-lg text-blood hover:bg-blood-light transition duration-200">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
      </a>
    </div>

    <!-- Form Tambah Artikel -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-blood-light">
      <form id="articleForm" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Judul Artikel -->
          <div class="md:col-span-2">
            <label for="title" class="block text-blood-darker text-sm font-medium mb-2">Judul Artikel <span class="text-blood">*</span></label>
            <input type="text" id="title" name="title" value="<?= $article["title"] ?>" required
              class="w-full px-4 py-2 border border-blood-light rounded-lg focus:outline-none focus:ring-2 focus:ring-blood focus:border-transparent"
              placeholder="Masukkan judul artikel">
          </div>

          <!-- URL Thumbnail -->
          <div class="md:col-span-2">
            <label for="thumbnail" class="block text-blood-darker text-sm font-medium mb-2">URL Thumbnail <span class="text-blood">*</span></label>
            <input type="url" id="thumbnail" name="thumbnail" value="<?= $article["thumbnail"] ?>" required
              class="w-full px-4 py-2 border border-blood-light rounded-lg focus:outline-none focus:ring-2 focus:ring-blood focus:border-transparent"
              placeholder="https://example.com/image.jpg">
          </div>

          <!-- Konten Artikel -->
          <div class="md:col-span-2">
            <label for="content" class="block text-blood-darker text-sm font-medium mb-2">Konten Artikel <span class="text-blood">*</span></label>
            <textarea id="content" name="content" rows="8" required
              class="w-full px-4 py-2 border border-blood-light rounded-lg focus:outline-none focus:ring-2 focus:ring-blood focus:border-transparent"
              placeholder="Tulis konten artikel disini..."><?= $article["content"] ?></textarea>
          </div>
        </div>

        <div class="flex justify-end space-x-4 pt-6 mt-6 border-t border-blood-light">
          <button type="reset" class="px-4 py-2 border border-blood rounded-lg text-blood hover:bg-blood-light transition duration-200">
            Reset
          </button>
          <button type="submit" class="px-4 py-2 bg-blood hover:bg-blood-dark text-white rounded-lg transition duration-200 shadow-md">
            <i class="fas fa-save mr-2"></i>Simpan Artikel
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Preview thumbnail
    document.getElementById('thumbnail').addEventListener('input', function(e) {
      const preview = document.getElementById('thumbnailPreview');
      preview.src = e.target.value || 'https://via.placeholder.com/300x150';
    });

    // Form submission
    document.getElementById('articleForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const form = document.getElementById("articleForm");
      form.action = "edit.php?id=<?= $article["id"] ?>";
      form.method = "post";
      form.submit();
    });
  </script>
</body>

</html>