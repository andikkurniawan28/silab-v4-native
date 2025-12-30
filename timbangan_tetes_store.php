<?php
include('session_manager.php');

// Ambil data dari form
$bruto = floatval($_POST['bruto']);
$netto = floatval($_POST['netto']);

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
    INSERT INTO mollases (bruto, tarra, netto)
    VALUES (?, ?, ?)
");

$stmt->bind_param("ddd", $bruto, $tarra, $netto);
$stmt->execute();

// Flash message
$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];

header("Location: timbangan_tetes_index.php");
exit;
