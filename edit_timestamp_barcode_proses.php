<?php
include('session_manager.php');

if (!isset($_POST['id'], $_POST['created_at'])) {
    die('Data tidak lengkap');
}

$id             = intval($_POST['id']);
$created_at     = mysqli_real_escape_string($conn, $_POST['created_at']);

$sql = "
    UPDATE analisa_off_farm_new
    SET created_at='$created_at'
    WHERE id='$id'
";

if (!$conn->query($sql)) {
    die('Gagal update timestamp: ' . $conn->error);
}

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];
header("Location: barcode_index.php?success=1");
exit;
