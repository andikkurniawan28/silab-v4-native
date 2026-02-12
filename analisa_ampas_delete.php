<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    'Kabag', 
    'Kasie', 
    'Kasubsie', 
    'Admin QC', 
    'Koordinator QC', 
    'Mandor Off Farm', 
    'Analis Off Farm', 
    // 'Mandor On Farm', 
    // 'Analis On Farm', 
    // 'Operator Pabrikasi',
    // 'Staff Teknik',
    // 'Staff Tanaman',
    // 'Staff TUK',
    // 'Direksi',
    // 'Tamu',
    ]);


if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'ID analisa tidak valid';
    header('Location: analisa_ampas_index.php');
    exit;
}

$sample_id = intval($_GET['id']); // INI = analisa_off_farm_new.id

/**
 * Reset nilai analisa ampas
 */
$update = $conn->query("
    UPDATE analisa_off_farm_new
    SET `%Air` = NULL,
        `%Zk` = NULL,
        Pol_Ampas = NULL
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

header('Location: analisa_ampas_index.php');
exit;
