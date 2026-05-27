<?php
include('session_manager.php');

$sample_id = intval($_POST['sample_id']);

$pH = ($_POST['pH'] !== '') 
    ? "'" . $conn->real_escape_string($_POST['pH']) . "'" 
    : "NULL";

$Turbidity = ($_POST['Turbidity'] !== '') 
    ? "'" . $conn->real_escape_string($_POST['Turbidity']) . "'" 
    : "NULL";

/**
 * Update analisa
 */
$conn->query("
    UPDATE analisa_off_farm_new
    SET 
        `pH` = $pH,
        `Turbidity` = $Turbidity
    WHERE id = $sample_id
");

$_SESSION['success'] = 'Analisa pH berhasil disimpan';
header('Location: analisa_ph_index.php');
exit;