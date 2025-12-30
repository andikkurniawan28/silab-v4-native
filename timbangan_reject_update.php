<?php
include('session_manager2.php');

$id    = intval($_POST['id']);
$bruto = floatval($_POST['bruto']);
$netto = floatval($_POST['netto']);

// Hitung ulang tarra (WAJIB)
$tarra = $bruto - $netto;

// Validasi
if ($tarra < 0) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'title' => 'Gagal',
        'message' => 'Netto tidak boleh lebih besar dari bruto'
    ];
    header("Location: timbangan_reject_edit.php?id=$id");
    exit;
}

$stmt = $conn->prepare("
    UPDATE in_process_weighings
    SET bruto = ?, tarra = ?, netto = ?
    WHERE id = ?
");
$stmt->bind_param("dddi", $bruto, $tarra, $netto, $id);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diperbarui'
];

header("Location: timbangan_reject_index.php");
exit;
