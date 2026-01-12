<?php
include('db.php');

$draw   = intval($_POST['draw'] ?? 1);
$start  = intval($_POST['start'] ?? 0);
$limit  = intval($_POST['length'] ?? 10);

/* =========================================
   BASE TABLE — 1000 DATA TERAKHIR
========================================= */
$baseTable = "
    (
        SELECT *
        FROM production_50
        ORDER BY id DESC
        LIMIT 1000
    ) AS p
";

/* =========================================
   TOTAL DATA (maks 1000)
========================================= */
$totalData = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
")->fetch_row()[0];

/* =========================================
   QUERY DATA
========================================= */
$q = $conn->query("
    SELECT *
    FROM $baseTable
    ORDER BY id DESC
    LIMIT $start, $limit
");

/* =========================================
   FORMAT DATA
========================================= */
$data = [];
while ($r = $q->fetch_assoc()) {
    $data[] = [
        'id'         => $r['id'],
        'created_at' => date('d-m-Y H:i', strtotime($r['created_at'])),
        'cronus_1'   => number_format($r['cronus_1']),
        'cronus_2'   => number_format($r['cronus_2']),
        'cronus_3'   => number_format($r['cronus_3']),
        'value'      => number_format($r['value'], 2),
        'action'     => '
            <a href="product50_delete.php?id='.$r['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data?\')">
               Hapus
            </a>
        '
    ];
}

/* =========================================
   OUTPUT JSON
========================================= */
echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => $totalData,
    'recordsFiltered' => $totalData,
    'data'            => $data
]);
