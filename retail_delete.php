<?php
include('session_manager.php');

$id = intval($_GET['id']);

$conn->query("DELETE FROM retail WHERE id=$id");

$_SESSION['flash'] = [
    'type'=>'success',
    'title'=>'Berhasil',
    'message'=>'Data berhasil dihapus'
];

header('Location: retail_index.php');
exit;
