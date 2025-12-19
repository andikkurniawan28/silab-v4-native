<?php
include('session_manager.php');

$stmt = $conn->prepare("DELETE FROM roles WHERE id=?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: role_index.php");
exit;
