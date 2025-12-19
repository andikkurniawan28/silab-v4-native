<?php
include('session_manager.php');

$id = intval($_GET['id']);

$conn->query("
    UPDATE analisa_off_farm_new
    SET SO2 = NULL
    WHERE id = $id
");

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data SO₂ berhasil dihapus'
];

header('Location: analisa_so2_index.php');
exit;
