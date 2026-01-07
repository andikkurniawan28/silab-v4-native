<?php
include('session_manager2.php');

// Ambil data dari form
$value = floatval($_POST['value']);
$created_at = $_POST['created_at'];

// Validasi sederhana
if ($value < 0) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'title' => 'Gagal',
        'message' => 'Value harus diisi'
    ];
    header("Location: timbangan_rs_in_tambah.php");
    exit;
}

// Simpan ke database
$stmt = $conn->prepare("
    INSERT INTO weighing_test (value, created_at)
    VALUES (?, ?)
");

$stmt->bind_param("ds", $value, $created_at);
$stmt->execute();

// Flash message
$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];

header("Location: timbangan_rs_in_index.php");
exit;
