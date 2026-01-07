<?php
include('session_manager2.php');

// Ambil data dari form
$bruto = floatval($_POST['bruto']);
$netto = floatval($_POST['netto']);
$created_at = $_POST['created_at'];
$line = "rs_out";

// Hitung tarra di server (aman walau JS dimatikan)
$tarra = $bruto - $netto;

// Validasi sederhana
if ($tarra < 0) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'title' => 'Gagal',
        'message' => 'Netto tidak boleh lebih besar dari bruto'
    ];
    header("Location: timbangan_reject_tambah.php");
    exit;
}

// Simpan ke database
$stmt = $conn->prepare("
    INSERT INTO in_process_weighings (bruto, tarra, netto, created_at, line)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("dddss", $bruto, $tarra, $netto, $created_at, $line);
$stmt->execute();

// Flash message
$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];

header("Location: timbangan_reject_index.php");
exit;
