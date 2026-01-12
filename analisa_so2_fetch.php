<?php
include('db.php');

/**
 * ===============================
 * DATATABLE (LIMIT 1000)
 * ===============================
 */
$limit = intval($_POST['length'] ?? 10);
$start = intval($_POST['start'] ?? 0);
$draw  = intval($_POST['draw'] ?? 1);

/**
 * BASE TABLE — 1000 DATA TERAKHIR YANG PUNYA SO2
 */
$baseTable = "
    (
        SELECT DISTINCT a.*
        FROM analisa_off_farm_new a
        JOIN materials m ON m.id = a.material_id
        JOIN methods md ON md.material_id = m.id
        JOIN indicators i ON i.id = md.indicator_id
        WHERE i.name = 'SO2'
        ORDER BY a.id DESC
        LIMIT 1000
    ) AS a
";

/**
 * TOTAL DATA (maks 1000)
 */
$totalData = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
")->fetch_row()[0];

/**
 * DATA
 */
$q = $conn->query("
    SELECT
        a.id,
        a.created_at,
        a.SO2,
        m.name AS material
    FROM $baseTable
    JOIN materials m ON m.id = a.material_id
    ORDER BY a.id DESC
    LIMIT $start, $limit
");

$data = [];
while ($r = $q->fetch_assoc()) {
    $data[] = [
        'id'         => $r['id'],
        'created_at' => date('d-m-Y H:i', strtotime($r['created_at'])),
        'material'   => $r['material'],
        'SO2'        => $r['SO2'] !== null ? $r['SO2'] : '-',
        'action'     => '
            <a href="analisa_so2_delete.php?id='.$r['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data?\')">
               Hapus
            </a>
        '
    ];
}

/**
 * RESPONSE
 */
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalData,
    "recordsFiltered" => $totalData,
    "data"            => $data
]);
