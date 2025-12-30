<?php
include('session_manager.php');

$id = $_GET['id'];

$conn->query("
    DELETE FROM keliling_proses
    WHERE id='$id'
");

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: keliling_proses_index.php");
exit;