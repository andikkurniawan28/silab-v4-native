<?php
include('session_manager.php');

$id              = intval($_POST['id']);
$brix_ari       = floatval($_POST['brix_ari']);
$pol_ari        = floatval($_POST['pol_ari']);
$pol_baca_ari   = floatval($_POST['pol_baca_ari']);
$rendemen_ari   = floatval($_POST['rendemen_ari']);

$stmt = $conn->prepare("
    UPDATE analisa_on_farms 
    SET 
        brix_ari = ?, 
        pol_ari = ?, 
        pol_baca_ari = ?, 
        rendemen_ari = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ddddi",
    $brix_ari,
    $pol_ari,
    $pol_baca_ari,
    $rendemen_ari,
    $id
);

$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];

header("Location: ari2_index.php");
exit;
