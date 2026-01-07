<?php
include('session_manager2.php');

$id    = intval($_POST['id']);
$value = floatval($_POST['value']);
$created_at = $_POST['created_at'];

// Validasi
if ($value < 0) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'title' => 'Gagal',
        'message' => 'Netto tidak boleh lebih besar dari value'
    ];
    header("Location: timbangan_rs_in_edit.php?id=$id");
    exit;
}

$stmt = $conn->prepare("
    UPDATE weighing_test
    SET value = ?, created_at = ? 
    WHERE id = ?
");
$stmt->bind_param("dsi", $value, $created_at, $id);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diperbarui'
];

header("Location: timbangan_rs_in_index.php");
exit;
