<?php
session_start();

// penghalang akses ke register/login (ketika sudah login)
if (isset($_SESSION["username"])) {
  // admin/manager
  if (isset($_SESSION["role"])) {
    header("Location: ../admin/index.php");
    exit;
  }

  // user
  header("Location: ../index.php");
  exit;
}
