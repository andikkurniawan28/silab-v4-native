<?php
session_start();
include 'db.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

// Ambil data request
$material_id = $_POST['material_id'];
$user_id     = $_SESSION['user_id'];

// Pengganti generateHour()
function generateHour()
{
    return date('Y-m-d H:i:00');
}

$created_at     = generateHour();
$timestamp_riil = date('Y-m-d H:i:s');

/**
 * 1. Insert ke tabel samples
 */
$querySample = "
    INSERT INTO samples (material_id, user_id, created_at, timestamp_riil)
    VALUES ('$material_id', '$user_id', '$created_at', '$timestamp_riil')
";

if (!mysqli_query($conn, $querySample)) {
    die("Gagal insert sample: " . mysqli_error($conn));
}

// Ambil ID sample terakhir
$sample_id = mysqli_insert_id($conn);

/**
 * 2. Insert ke tabel analisa_off_farms
 */
$queryAnalisa = "
    INSERT INTO analisa_off_farms (sample_id)
    VALUES ('$sample_id')
";

mysqli_query($conn, $queryAnalisa);

/**
 * 3. Ambil data sample untuk ditampilkan
 */
$queryData = "
    SELECT *
    FROM samples
    WHERE id = '$sample_id'
    LIMIT 1
";

$result = mysqli_query($conn, $queryData);
$data   = mysqli_fetch_assoc($result);

// Tampilkan ke view
include 'cetak_barcode_show.php';
