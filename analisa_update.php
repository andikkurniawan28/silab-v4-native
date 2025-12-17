<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    die('Akses ditolak');
}

if (!isset($_POST['id'])) {
    die('ID analisa tidak valid');
}

$id = intval($_POST['id']);

/**
 * Ambil daftar kolom VALID dari tabel
 */
$validCols = [];
$res = $conn->query("SHOW COLUMNS FROM analisa_off_farm_new");
while ($c = $res->fetch_assoc()) {
    $validCols[] = $c['Field'];
}

/**
 * Siapkan data update
 */
$set = [];

if (!empty($_POST['indicator']) && is_array($_POST['indicator'])) {

    foreach ($_POST['indicator'] as $column => $value) {

        // validasi kolom dari DB
        if (!in_array($column, $validCols)) {
            continue;
        }

        $value = trim($value);

        // jika kosong → NULL
        if ($value === '') {
            $set[] = "`$column` = NULL";
        } else {
            $value = mysqli_real_escape_string($conn, $value);
            $set[] = "`$column` = '$value'";
        }
    }
}

if (empty($set)) {
    die('Tidak ada data valid untuk diupdate');
}

/**
 * Update TANPA menyentuh is_verified
 */
$sql = "
    UPDATE analisa_off_farm_new
    SET " . implode(', ', $set) . "
    WHERE id = '$id'
";

if (!$conn->query($sql)) {
    die('Gagal update: ' . $conn->error);
}

header("Location: analisa_index.php");
exit;
