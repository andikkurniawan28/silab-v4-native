<?php
include('session_manager.php');

$id = intval($_GET['id']);

$conn->query("DELETE FROM balances WHERE id=$id");

$_SESSION['flash'] = [
    'type'=>'success',
    'title'=>'Berhasil',
    'message'=>'Data balance berhasil dihapus'
];

header('Location: balance_index.php');
exit;
