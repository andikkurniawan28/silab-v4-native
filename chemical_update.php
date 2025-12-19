<?php
include('session_manager.php');

$stmt = $conn->prepare("
    UPDATE chemicals SET name=? WHERE id=?
");
$stmt->bind_param("si", $_POST['name'], $_POST['id']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];
header("Location: chemical_index.php");
exit;
