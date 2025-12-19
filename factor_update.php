<?php
include('session_manager.php');

$stmt = $conn->prepare("
    UPDATE factors SET name=?, value=? WHERE id=?
");
$stmt->bind_param("sdi", $_POST['name'], $_POST['value'], $_POST['id']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];
header("Location: factor_index.php");
exit;
