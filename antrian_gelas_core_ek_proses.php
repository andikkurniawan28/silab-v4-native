<?php
include('session_manager.php');

$rfid  = $_POST['rfid'];
$jenis = 'K';

$stmt = $conn->prepare("INSERT INTO kartu_cores (rfid, jenis) VALUES (?, ?)");
$stmt->bind_param("ss", $rfid, $jenis);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header("Location: antrian_gelas_core_ek_index.php");
exit;
