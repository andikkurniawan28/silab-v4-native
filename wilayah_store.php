<?php
include('session_manager.php');

$stmt = $conn->prepare("INSERT INTO kuds (name, code) VALUES (?, ?)");
$stmt->bind_param("ss", $_POST['name'], $_POST['code']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header("Location: wilayah_index.php");
exit;
