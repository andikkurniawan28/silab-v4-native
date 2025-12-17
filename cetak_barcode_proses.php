<?php
session_start();
include 'db.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

// Validasi request
if (!isset($_POST['material_id'])) {
    die("Material tidak valid");
}

$material_id = $_POST['material_id'];
$user_id     = $_SESSION['user_id'];

function generateHour()
{
    return date('Y-m-d H:i:00');
}

$created_at     = generateHour();
$timestamp_riil = date('Y-m-d H:i:s');

/**
 * Insert ke tabel analisa_off_farm_new
 */
$sql = "
    INSERT INTO analisa_off_farm_new
    (material_id, user_id, created_at, timestamp_riil)
    VALUES
    ('$material_id', '$user_id', '$created_at', '$timestamp_riil')
";

if (!$conn->query($sql)) {
    die("Gagal insert sample: " . $conn->error);
}

// Ambil ID sample terakhir
$sample_id = $conn->insert_id;

/**
 * REDIRECT ke halaman cetak
 */
header("Location: cetak_barcode_show.php?id=$sample_id");
exit;
