<?php
require_once("../../db/config.php");
require_once("../auth.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["id"];

  $delete_query = "DELETE FROM users WHERE id = ?";
  $stmt = $db->prepare($delete_query);
  $stmt->bind_param("s", $id);
  $result = $stmt->execute();

  if ($result) {
    echo "<script>alert('Data berhasil dihapus!'); window.location.href='index.php';</script>";
    exit;
  } else {
    echo "<script>alert('Data gagal dihapus!'); window.history.back();</script>";
    exit;
  }
}
