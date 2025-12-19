<?php
include('db.php');

$limit  = isset($_POST['length']) ? intval($_POST['length']) : 10;
$start  = isset($_POST['start'])  ? intval($_POST['start'])  : 0;

/**
 * TOTAL DATA (hanya material yang punya indikator SO2)
 */
$totalData = $conn->query("
    SELECT COUNT(DISTINCT a.id)
    FROM analisa_off_farm_new a
    JOIN materials m ON m.id = a.material_id
    JOIN methods md ON md.material_id = m.id
    JOIN indicators i ON i.id = md.indicator_id
    WHERE i.name = 'SO2'
")->fetch_row()[0];

/**
 * DATA
 */
$sql = "
    SELECT DISTINCT
        a.id,
        a.created_at,
        a.SO2,
        m.name AS material
    FROM analisa_off_farm_new a
    JOIN materials m ON m.id = a.material_id
    JOIN methods md ON md.material_id = m.id
    JOIN indicators i ON i.id = md.indicator_id
    WHERE i.name = 'SO2'
    ORDER BY a.id DESC
    LIMIT $start, $limit
";

$q = $conn->query($sql);
$data = [];

while ($r = $q->fetch_assoc()) {
    $data[] = [
        'id' => $r['id'],
        'created_at' => date('d-m-Y H:i', strtotime($r['created_at'])),
        'material' => $r['material'],
        'SO2' => $r['SO2'] !== null ? $r['SO2'] : '-',
        'action' => '
            <a href="analisa_so2_delete.php?id='.$r['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data?\')">
               Hapus
            </a>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalData,
    "data" => $data
]);
