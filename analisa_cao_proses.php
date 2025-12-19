<?php
include('session_manager.php');

$sample_id       = intval($_POST['sample_id']);
$volume_titrasi  = floatval($_POST['volume_titrasi']);
$pengenceran     = floatval($_POST['pengenceran']);

/**
 * Ambil faktor EDTA
 */
$faktorQ = $conn->query("
    SELECT value
    FROM factors
    WHERE name = 'Faktor EDTA'
    ORDER BY id DESC
    LIMIT 1
");

$faktor = $faktorQ->fetch_assoc()['value'] ?? 0;

/**
 * Hitung CaO
 * rumus: volume_titrasi * faktor * 200 * pengenceran
 */
$cao = $volume_titrasi * $faktor * 200 * $pengenceran;

/**
 * Update analisa
 */
$conn->query("
    UPDATE analisa_off_farm_new
    SET CaO = '$cao'
    WHERE id = $sample_id
");

$_SESSION['success'] = 'Analisa CaO berhasil disimpan';
header('Location: analisa_cao_index.php');
exit;
