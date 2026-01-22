<?php

include('db.php');

/**
 * =========================
 * PREPARE TIME RANGE
 * =========================
 */
$tanggal_pengujian = $_POST['tanggal_pengujian'];
$tanggal_terima = $_POST['tanggal_terima'];
$tanggal_pengujian = $_POST['tanggal_pengujian'];
$tanggal_cetak = $_POST['tanggal_cetak'];
$nomor_dokumen = $_POST['nomor_dokumen'];

$date["current"] = $tanggal_pengujian." 06:00";
$date["tomorrow"] = date("Y-m-d H:i", strtotime($date["current"] . "+24 hours"));

$sql = "
    SELECT 
        a.*,
        m.name AS material_name
    FROM analisa_off_farm_new a
    INNER JOIN materials m 
        ON a.material_id = m.id
    WHERE a.created_at BETWEEN ? AND ?
      AND a.material_id IN (154,156)
    ORDER BY a.created_at ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $date['current'], $date['tomorrow']);
$stmt->execute();
$result = $stmt->get_result();

/**
 * =========================
 * BUILD OBJECT RESULT
 * =========================
 */
$data = [];

while ($row = $result->fetch_object()) {
    $data[] = $row;
}

include('coa_kapur_show.php');
