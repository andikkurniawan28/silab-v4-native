<?php
include('session_manager.php');

$stmt = $conn->prepare("DELETE FROM kartu_cores");
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: antrian_gelas_core_ek_index.php");
exit;
