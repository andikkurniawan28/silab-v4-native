<?php
include('session_manager.php');

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $_GET['id']);

$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: user_index.php");
exit;
