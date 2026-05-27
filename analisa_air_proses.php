<?php
include('session_manager.php');

$sample_id          = intval($_POST['sample_id']);
$air                = floatval($_POST['air']);

/**
 * Update analisa
 */
$conn->query("
    UPDATE analisa_off_farm_new
    SET `%Air` = '$air'
    WHERE id = $sample_id
");

$_SESSION['success'] = 'Analisa %Air berhasil disimpan';
header('Location: analisa_air_index.php');
exit;
