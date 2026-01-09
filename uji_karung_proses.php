<?php
include('session_manager.php');
include('db.php');

/* ===============================
   VALIDASI DASAR
================================ */
if (!isset($_POST['dimensi']) || !is_array($_POST['dimensi'])) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'title' => 'Gagal',
        'message' => 'Data tidak valid'
    ];
    header('Location: uji_karung_index.php');
    exit;
}

$tanggal     = $_POST['tanggal'];
$kedatangan  = $_POST['kedatangan'];
$batch       = $_POST['batch'];

/* ===============================
   LOOP DATA
================================ */
foreach ($_POST['dimensi'] as $nomor => $dimensi) {

    $berat  = $_POST['berat'][$nomor]  ?? [];
    $tebal  = $_POST['tebal'][$nomor]  ?? [];
    $mesh   = $_POST['mesh'][$nomor]   ?? [];
    $denier = $_POST['denier'][$nomor] ?? [];

    /* ---------- NILAI NUMERIK ---------- */
    $p_nilai_outer = isset($dimensi['p_nilai_outer']) ? floatval($dimensi['p_nilai_outer']) : null;
    $l_nilai_outer = isset($dimensi['l_nilai_outer']) ? floatval($dimensi['l_nilai_outer']) : null;
    $p_nilai_inner = isset($dimensi['p_nilai_inner']) ? floatval($dimensi['p_nilai_inner']) : null;
    $l_nilai_inner = isset($dimensi['l_nilai_inner']) ? floatval($dimensi['l_nilai_inner']) : null;

    $berat_outer = isset($berat['outer']) ? floatval($berat['outer']) : null;
    $berat_inner = isset($berat['inner']) ? floatval($berat['inner']) : null;

    $tebal_outer = isset($tebal['outer']) ? floatval($tebal['outer']) : null;
    $tebal_inner = isset($tebal['inner']) ? floatval($tebal['inner']) : null;

    $mesh_alas   = isset($mesh['alas']) ? floatval($mesh['alas']) : null;
    $mesh_tinggi= isset($mesh['tinggi']) ? floatval($mesh['tinggi']) : null;

    $denier_nilai = isset($denier['nilai']) ? floatval($denier['nilai']) : null;

    /* ---------- KETERANGAN (1 / 0) ---------- */
    $p_ket_outer = ($p_nilai_outer > 92.15 && $p_nilai_outer <= 101.85) ? 1 : 0;
    $l_ket_outer = ($l_nilai_outer > 54.15 && $l_nilai_outer <= 59.85) ? 1 : 0;
    $p_ket_inner = ($p_nilai_inner > 104.5 && $p_nilai_inner <= 115.5) ? 1 : 0;
    $l_ket_inner = ($l_nilai_inner > 57 && $l_nilai_inner <= 63) ? 1 : 0;

    $berat_outer_ket = ($berat_outer > 104.5 && $berat_outer <= 115.5) ? 1 : 0;
    $berat_inner_ket = ($berat_inner > 34.2 && $berat_inner <= 37.8) ? 1 : 0;

    $tebal_outer_ket = ($tebal_outer > 0.166 && $tebal_outer <= 0.183) ? 1 : 0;
    $tebal_inner_ket = ($tebal_inner > 0.029 && $tebal_inner <= 0.032) ? 1 : 0;

    $mesh_ket_alas   = ($mesh_alas > 11.4 && $mesh_alas <= 12.6) ? 1 : 0;
    $mesh_ket_tinggi= ($mesh_tinggi > 11.4 && $mesh_tinggi <= 12.6) ? 1 : 0;

    $denier_ket = ($denier_nilai > 855 && $denier_nilai <= 945) ? 1 : 0;

    /* ---------- INSERT ---------- */
    $sql = "
        INSERT INTO uji_karungs SET
            tanggal = '$tanggal',
            kedatangan = '$kedatangan',
            batch = '$batch',
            nomor = '$nomor',

            p_nilai_outer = ".($p_nilai_outer ?? 'NULL').",
            p_ket_outer = $p_ket_outer,
            l_nilai_outer = ".($l_nilai_outer ?? 'NULL').",
            l_ket_outer = $l_ket_outer,

            p_nilai_inner = ".($p_nilai_inner ?? 'NULL').",
            p_ket_inner = $p_ket_inner,
            l_nilai_inner = ".($l_nilai_inner ?? 'NULL').",
            l_ket_inner = $l_ket_inner,

            berat_outer = ".($berat_outer ?? 'NULL').",
            berat_outer_ket = $berat_outer_ket,
            berat_inner = ".($berat_inner ?? 'NULL').",
            berat_inner_ket = $berat_inner_ket,

            raw_outer = ".(!empty($tebal['raw_outer']) ? "'".$conn->real_escape_string($tebal['raw_outer'])."'" : 'NULL').",
            tebal_outer = ".($tebal_outer ?? 'NULL').",
            tebal_outer_ket = $tebal_outer_ket,

            raw_inner = ".(!empty($tebal['raw_inner']) ? "'".$conn->real_escape_string($tebal['raw_inner'])."'" : 'NULL').",
            tebal_inner = ".($tebal_inner ?? 'NULL').",
            tebal_inner_ket = $tebal_inner_ket,

            mesh_alas = ".($mesh_alas ?? 'NULL').",
            mesh_ket_alas = $mesh_ket_alas,
            mesh_tinggi = ".($mesh_tinggi ?? 'NULL').",
            mesh_ket_tinggi = $mesh_ket_tinggi,

            denier_nilai = ".($denier_nilai ?? 'NULL').",
            denier_ket = $denier_ket,

            created_at = NOW()
    ";

    $conn->query($sql);
}

/* ===============================
   FLASH & REDIRECT
================================ */
$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data uji karung berhasil disimpan'
];

header('Location: uji_karung_index.php');
exit;
