<?php
include('session_manager.php');

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM mollases WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];

header("Location: timbangan_tetes_index.php");
exit;
