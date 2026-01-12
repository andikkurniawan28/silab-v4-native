<?php
include('session_manager.php');

$stmt = $conn->prepare("DELETE FROM kuds WHERE id=?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: wilayah_index.php");
exit;
