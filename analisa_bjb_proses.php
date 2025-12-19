<?php
include('session_manager.php');

$sample_id = intval($_POST['sample_id']);
$bjb = $_POST['value'] !== '' ? floatval($_POST['value']) : null;
$cv  = $_POST['cv'] !== '' ? floatval($_POST['cv']) : null;

$conn->query("
    UPDATE analisa_off_farm_new
    SET
        BJB = ".($bjb !== null ? "'$bjb'" : "NULL").",
        CV  = ".($cv !== null ? "'$cv'" : "NULL")."
    WHERE sample_id = $sample_id
");

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Analisa BJB berhasil disimpan'
];

header('Location: analisa_bjb_index.php');
exit;
