<?php
session_start();

// penghalang akses ke user (ketika belum login)
// if (!isset($_SESSION["username"])) {
//   header("Location: auth/login.php");
//   exit;
// }

if (isset($_SESSION["role"])) {
  header("Location: admin/index.php");
  exit;
}
