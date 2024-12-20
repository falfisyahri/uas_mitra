<?php
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "dbuas_fatah_alfi_syahri";

$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (!$koneksi->set_charset("utf8mb4")) {
    die("Gagal menyetel charset: " . $koneksi->error);
}
?>
