<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "donor_darah";

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}
