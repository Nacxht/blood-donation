<?php
session_start();

// mengecek apakah user sudah login atau belum
if (!isset($_SESSION["username"])) {
  header("Location: ../auth/login.php");
  exit;
}

// mengecek apakah user merupakan golongan admin atau bukan
if (!isset($_SESSION["role"])) {
  header("Location: ../index.php");
  exit;
}
