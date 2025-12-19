<?php
include('session_manager.php');

$sample_id = intval($_POST['sample_id']);
$v1 = floatval($_POST['v1']);
$v2 = floatval($_POST['v2']);

/**
 * Ambil faktor Yodium
 */
$fQ = $conn->query("
    SELECT value
    FROM factors
    WHERE name = 'Faktor Yodium'
    ORDER BY id DESC
    LIMIT 1
");

$faktor = $fQ->fetch_assoc()['value'] ?? null;

if ($faktor === null) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Gagal',
        'message' => 'Faktor Yodium tidak ditemukan'
    ];
    header('Location: analisa_so2_index.php');
    exit;
}

/**
 * Hitung SO2
 */
$so2 = ($v2 - $v1) * $faktor * 20;

/**
 * Update data
 */
$conn->query("
    UPDATE analisa_off_farm_new
    SET SO2 = '$so2'
    WHERE id = $sample_id
");

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Analisa SO₂ berhasil disimpan'
];

header('Location: analisa_so2_index.php');
exit;
