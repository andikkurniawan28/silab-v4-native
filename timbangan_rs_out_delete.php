<?php
include('session_manager2.php');

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM in_process_weighings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];

header("Location: timbangan_rs_out_index.php");
exit;
