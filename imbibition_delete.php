<?php
include('session_manager.php');

$id = intval($_GET['id']);

$conn->query("DELETE FROM imbibitions WHERE id=$id");

$_SESSION['flash'] = [
    'type'=>'success',
    'title'=>'Berhasil',
    'message'=>'Data imbibition berhasil dihapus'
];

header('Location: imbibition_index.php');
exit;
