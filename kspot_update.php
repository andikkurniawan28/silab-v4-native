<?php
include('session_manager.php');

$stmt = $conn->prepare("
    UPDATE kspots SET name=? WHERE id=?
");
$stmt->bind_param("si", $_POST['name'], $_POST['id']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];
header("Location: kspot_index.php");
exit;
