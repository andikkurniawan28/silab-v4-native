<?php
include('session_manager.php');

$conn->begin_transaction();

$conn->query("
    DELETE FROM methods WHERE material_id=".$_GET['id']
);

$stmt = $conn->prepare("
    DELETE FROM materials WHERE id=?
");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();

$conn->commit();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: material_index.php");
exit;
