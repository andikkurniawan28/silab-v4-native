<?php
include('session_manager.php');

$stmt = $conn->prepare("DELETE FROM kartu_aris");
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: antrian_gelas_ari_ek_index.php");
exit;
