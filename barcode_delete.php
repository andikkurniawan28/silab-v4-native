<?php
include('session_manager.php');

$id = $_GET['id'];

$conn->query("
    DELETE FROM analisa_off_farm_new
    WHERE id='$id'
");

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: barcode_index.php");
exit;