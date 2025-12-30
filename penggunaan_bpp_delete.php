<?php
include('session_manager.php');

$id = $_GET['id'];

$conn->query("
    DELETE FROM penggunaan_bpp
    WHERE id='$id'
");

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header("Location: penggunaan_bpp_index.php");
exit;