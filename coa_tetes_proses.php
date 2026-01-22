<?php

include('db.php');

/**
 * =========================
 * PREPARE TIME RANGE
 * =========================
 */
$created_at = $_POST['date'];
$nomor_dokumen = $_POST['nomor_dokumen'];

if (!$created_at) {
    die('created_at wajib ada');
}

$time_start = $created_at . ' 05:00:00';
$time_end   = date('Y-m-d H:i:s', strtotime($time_start . ' +24 hours'));

/**
 * =========================
 * AMBIL MATERIAL DASAR
 * =========================
 */
$sql_material = "
    SELECT id AS code, name
    FROM materials
    WHERE id IN (77,78,79)
";

$materials = $conn->query($sql_material)->fetch_all(MYSQLI_ASSOC);

/**
 * =========================
 * QUERY RATA-RATA PER MATERIAL
 * =========================
 */
$sql_avg = "
    SELECT
        material_id,
        AVG(`%Brix`) AS brix,
        AVG(TSAI)    AS tsai,
        AVG(OD)      AS od
    FROM analisa_off_farm_new
    WHERE material_id IN (77,78,79)
      AND created_at BETWEEN ? AND ?
    GROUP BY material_id
";

$stmt = $conn->prepare($sql_avg);
$stmt->bind_param('ss', $time_start, $time_end);
$stmt->execute();
$result = $stmt->get_result();

/**
 * =========================
 * INDEX HASIL BERDASARKAN material_id
 * =========================
 */
$avg_map = [];
while ($row = $result->fetch_assoc()) {
    $avg_map[$row['material_id']] = $row;
}

/**
 * =========================
 * MERGE KE STRUCTURE SAMPLES
 * =========================
 */
$samples = [];

foreach ($materials as $m) {
    $mid = $m['code'];

    $samples[] = (object)[
        'code' => $mid,
        'name' => $m['name'],
        'brix' => $avg_map[$mid]['brix'] ?? null,
        'tsai' => $avg_map[$mid]['tsai'] ?? null,
        'od'   => $avg_map[$mid]['od']   ?? null,
    ];
}

/**
 * =========================
 * VIEW
 * =========================
 */
include 'coa_tetes_show.php';
