<?php
/**
 * PROSES ANALISA AMPAS – JOHN PAYNE
 * Versi Native PHP
 */

include('session_manager.php'); // sudah include db + session

// Ambil data
$sample_id            = intval($_POST['sample_id']);
$pol_ampas            = floatval($_POST['pol_ampas']);
$pol_persen           = floatval($_POST['pol_persen']);
$brix                 = floatval($_POST['brix']);
$berat_sampel         = floatval($_POST['berat_sampel']);
$berat_air            = floatval($_POST['berat_air']);
$berat_residual_juice = floatval($_POST['berat_residual_juice']);
$berat_kering         = floatval($_POST['berat_kering']);

// Update analisa_off_farm_new
$update = $conn->query("
    UPDATE analisa_off_farm_new
    SET 
        Pol_Ampas = '$pol_ampas',
        `%Pol` = '$pol_persen',
        `%Brix` = '$brix',
        berat_sampel = '$berat_sampel',
        berat_air = '$berat_air',
        berat_residual_juice = '$berat_residual_juice',
        `%Zk` = '$berat_kering'
    WHERE id = $sample_id
");

if ($update) {
    $_SESSION['flash'] = [
        'type' => 'success',
        'title' => 'Berhasil',
        'message' => 'Data berhasil diupdate'
    ];
} else {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Gagal Simpan',
        'message' => 'Data gagal disimpan'
    ];
}

header('Location: analisa_ampas_john_payne_index.php');
exit;
