<?php
include('session_manager.php');

// Ambil data dari form
$bruto = floatval($_POST['bruto']);
$netto = floatval($_POST['netto']);
$created_at = $_POST['created_at'];

// Hitung tarra di server (aman walau JS dimatikan)
$tarra = $bruto - $netto;

// Validasi sederhana
if ($tarra < 0) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'title' => 'Gagal',
        'message' => 'Netto tidak boleh lebih besar dari bruto'
    ];
    header("Location: timbangan_tetes_tambah.php");
    exit;
}

// Simpan ke database
$stmt = $conn->prepare("
    INSERT INTO mollases (bruto, tarra, netto, created_at)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("ddds", $bruto, $tarra, $netto, $created_at);
$stmt->execute();

// Flash message
$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];

header("Location: timbangan_tetes_index.php");
exit;
