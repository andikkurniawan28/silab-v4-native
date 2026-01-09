<?php
include('session_manager.php');

$id = intval($_GET['id']);

$conn->query("DELETE FROM production_50 WHERE id=$id");

$_SESSION['flash'] = [
    'type'=>'success',
    'title'=>'Berhasil',
    'message'=>'Data berhasil dihapus'
];

header('Location: product50_index.php');
exit;
