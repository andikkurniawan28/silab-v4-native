<?php
include('session_manager.php');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'ID analisa tidak valid';
    header('Location: analisa_ampas_john_payne_index.php');
    exit;
}

$sample_id = intval($_GET['id']); // INI = analisa_off_farm_new.id

/**
 * Reset nilai analisa ampas
 */
$update = $conn->query("
    UPDATE analisa_off_farm_new
    SET `Pol_Ampas` = NULL,
        `%Pol` = NULL,
        `%Brix` = NULL,
        `berat_sampel` = NULL,
        `berat_air` = NULL,
        `berat_residual_juice` = NULL,
        `%Zk` = NULL
    WHERE id = $sample_id
");

if ($update) {
    $_SESSION['flash'] = [
        'type' => 'success',
        'title' => 'Berhasil',
        'message' => 'Data berhasil disimpan'
    ];
} else {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Gagal simpan',
        'message' => 'Data gagal disimpan'
    ];
}

header('Location: analisa_ampas_john_payne_index.php');
exit;
