<?php
include('session_manager.php');

$id              = intval($_POST['id']);
$brix_core       = floatval($_POST['brix_core']);
$pol_core        = floatval($_POST['pol_core']);
$pol_baca_core   = floatval($_POST['pol_baca_core']);
$rendemen_core   = floatval($_POST['rendemen_core']);

$stmt = $conn->prepare("
    UPDATE analisa_on_farms 
    SET 
        brix_core = ?, 
        pol_core = ?, 
        pol_baca_core = ?, 
        rendemen_core = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ddddi",
    $brix_core,
    $pol_core,
    $pol_baca_core,
    $rendemen_core,
    $id
);

$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];

header("Location: ari1_index.php");
exit;
