<?php
include('session_manager2.php');

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM weighing_test WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];

header("Location: timbangan_rs_in_index.php");
exit;
