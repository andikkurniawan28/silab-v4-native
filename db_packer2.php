<?php
date_default_timezone_set('Asia/Jakarta');

// $host     = "192.168.20.234";
$host     = "192.168.29.250";
$username = "andik";
$password = "andik";
$database = "qc";

// Membuat koneksi
$conn2 = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn2) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Jika perlu, bisa aktifkan baris ini untuk testing
// echo "Koneksi berhasil";
?>
