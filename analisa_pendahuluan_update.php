<?php
include('session_manager.php');

$id              = intval($_POST['id']);
$brix_atas       = floatval($_POST['brix_atas']);
$pol_atas        = floatval($_POST['pol_atas']);
$pol_baca_atas   = floatval($_POST['pol_baca_atas']);
$rendemen_atas   = floatval($_POST['rendemen_atas']);
$brix_tengah       = floatval($_POST['brix_tengah']);
$pol_tengah        = floatval($_POST['pol_tengah']);
$pol_baca_tengah   = floatval($_POST['pol_baca_tengah']);
$rendemen_tengah   = floatval($_POST['rendemen_tengah']);
$brix_bawah       = floatval($_POST['brix_bawah']);
$pol_bawah        = floatval($_POST['pol_bawah']);
$pol_baca_bawah   = floatval($_POST['pol_baca_bawah']);
$rendemen_bawah   = floatval($_POST['rendemen_bawah']);

$stmt = $conn->prepare("
    UPDATE analisa_pendahuluans 
    SET 
        brix_atas = ?, 
        pol_atas = ?, 
        pol_baca_atas = ?, 
        rendemen_atas = ?,
        brix_tengah = ?, 
        pol_tengah = ?, 
        pol_baca_tengah = ?, 
        rendemen_tengah = ?,
        brix_bawah = ?, 
        pol_bawah = ?, 
        pol_baca_bawah = ?, 
        rendemen_bawah = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ddddddddddddi",
    $brix_atas,
    $pol_atas,
    $pol_baca_atas,
    $rendemen_atas,
    $brix_tengah,
    $pol_tengah,
    $pol_baca_tengah,
    $rendemen_tengah,
    $brix_bawah,
    $pol_bawah,
    $pol_baca_bawah,
    $rendemen_bawah,
    $id
);

$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];

header("Location: analisa_pendahuluan_index.php");
exit;
