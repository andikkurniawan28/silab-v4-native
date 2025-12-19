<?php
include('session_manager.php');

$stmt = $conn->prepare("
    DELETE FROM factors WHERE id=?
");
$stmt->bind_param("i", $_POST['id']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: factor_index.php");
exit;