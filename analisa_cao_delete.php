<?php
include('session_manager.php');

$id = intval($_GET['id']);

$conn->query("
    UPDATE analisa_off_farm_new
    SET CaO = NULL
    WHERE id = $id
");

$_SESSION['success'] = 'Analisa CaO berhasil dihapus';
header('Location: analisa_cao_index.php');
exit;
