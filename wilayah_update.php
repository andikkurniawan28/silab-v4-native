<?php
include('session_manager.php');

$stmt = $conn->prepare("UPDATE kuds SET name=?, code=? WHERE id=?");
$stmt->bind_param("ssi", $_POST['name'], $_POST['code'], $_POST['id']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];
header("Location: wilayah_index.php");
exit;
