<?php
include('session_manager.php');

$stmt = $conn->prepare("
    DELETE FROM chemicals WHERE id=?
");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: chemical_index.php");
exit;
