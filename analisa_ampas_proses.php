<?php
include('session_manager.php');

$sample_id = intval($_POST['sample_id']); // INI = analisa_off_farm_new.id
$air = floatval($_POST['air']);
$zk  = floatval($_POST['zk']);

/**
 * Ambil POL & material dari analisa
 */
$analisaQ = $conn->query("
    SELECT Pol, material_id
    FROM analisa_off_farm_new
    WHERE id = $sample_id
    LIMIT 1
");

$analisa = $analisaQ->fetch_assoc();
$pol_is_exist = $analisa['Pol'] ?? null;
$material_id  = $analisa['material_id'] ?? null;

if ($pol_is_exist === null) {
    $_SESSION['error'] = 'Pol tidak ditemukan!';
    header('Location: analisa_ampas_index.php');
    exit;
}

/**
 * Ambil indikator dari methods
 */
$indQ = $conn->query("
    SELECT indicator_id
    FROM methods
    WHERE material_id = $material_id
");

$indicators = [];
while ($i = $indQ->fetch_assoc()) {
    $indicators[] = $i['indicator_id'];
}

/**
 * Jika ada indikator 25 (Pol Ampas)
 */
if (in_array(25, $indicators)) {

    /**
     * Ambil factor Pol Ampas
     */
    $factor = $conn->query("
        SELECT value
        FROM factors
        WHERE name = 'Pol Ampas'
        ORDER BY id DESC
        LIMIT 1
    ")->fetch_assoc()['value'] ?? 0;

    /**
     * Ambil Brix Nira Gilingan 5 (material_id = 7)
     * Ambil data ke-2 dari belakang (before last)
     */
    $brixQ = $conn->query("
        SELECT `%Brix`
        FROM analisa_off_farm_new
        WHERE material_id = 7
          AND `%Brix` IS NOT NULL
          AND is_verified = 1
        ORDER BY created_at ASC
    ");

    $rows = [];
    while ($b = $brixQ->fetch_assoc()) {
        $rows[] = $b;
    }

    $brixValue = count($rows) > 1
        ? $rows[count($rows) - 2]['%Brix']
        : 0;

    /**
     * Hitung Pol Ampas
     */
    $pol_ampas = (($pol_is_exist / 2) * 0.0286 * ((10000 + $air) / 100) * 2.5)
                 + ($factor * $brixValue);

    $conn->query("
        UPDATE analisa_off_farm_new
        SET Pol_Ampas = '$pol_ampas',
            `%Zk` = '$zk'
        WHERE id = $sample_id
    ");
}
else {

    /**
     * Jika tidak ada indikator 25
     */
    $conn->query("
        UPDATE analisa_off_farm_new
        SET `%Air` = '$air',
            `%Zk`  = '$zk'
        WHERE id = $sample_id
    ");
}


$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header('Location: analisa_ampas_index.php');
exit;
