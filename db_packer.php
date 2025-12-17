<?php
$host     = "192.168.20.234";
$username = "andik";
$password = "andik";
$database = "silab";

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Jika perlu, bisa aktifkan baris ini untuk testing
// echo "Koneksi berhasil";
?>
