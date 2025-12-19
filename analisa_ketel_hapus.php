<?php
include('session_manager.php');

$id = intval($_GET['id']);

$conn->query("
    UPDATE analisa_off_farm_new
    SET
        `pH` = NULL,
        `TDS` = NULL,
        `Sadah` = NULL,
        `P2O5` = NULL,
        `Silika` = NULL
    WHERE id = $id
");

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil dihapus'
];
header('Location: analisa_ketel_index.php');
exit;
