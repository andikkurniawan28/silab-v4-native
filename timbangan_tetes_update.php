<?php
include('session_manager.php');

$id    = intval($_POST['id']);
$bruto = floatval($_POST['bruto']);
$netto = floatval($_POST['netto']);
$created_at = $_POST['created_at'];

// Hitung ulang tarra (WAJIB)
$tarra = $bruto - $netto;

// Validasi
if ($tarra < 0) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'title' => 'Gagal',
        'message' => 'Netto tidak boleh lebih besar dari bruto'
    ];
    header("Location: timbangan_tetes_edit.php?id=$id");
    exit;
}

$stmt = $conn->prepare("
    UPDATE mollases
    SET bruto = ?, tarra = ?, netto = ?, created_at = ?
    WHERE id = ?
");
$stmt->bind_param("dddsi", $bruto, $tarra, $netto, $created_at, $id);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diperbarui'
];

header("Location: timbangan_tetes_index.php");
exit;
