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
 * BASE TABLE — 1000 DATA TERAKHIR
 */
$baseTable = "
    (
        SELECT *
        FROM imbibitions
        ORDER BY id DESC
        LIMIT 1000
    ) AS i
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
    SELECT *
    FROM $baseTable
    ORDER BY id DESC
    LIMIT $start, $limit
");

$data = [];
while ($r = $q->fetch_assoc()) {
    $data[] = [
        'id' => $r['id'],
        'created_at' => date('d-m-Y H:i', strtotime($r['created_at'])),
        'totalizer' => number_format($r['totalizer']),
        'flow_imb' => $r['flow_imb'],
        'action' => '
            <a href="imbibition_delete.php?id='.$r['id'].'"
               onclick="return confirm(\'Hapus data?\')"
               class="btn btn-danger btn-sm">
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
