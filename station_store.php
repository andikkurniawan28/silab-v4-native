<?php
include('session_manager.php');

$stmt = $conn->prepare("INSERT INTO stations (name) VALUES (?)");
$stmt->bind_param("s", $_POST['name']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header("Location: station_index.php");
exit;
