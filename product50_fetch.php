<?php
include('db.php');

$limit = intval($_POST['length'] ?? 10);
$start = intval($_POST['start'] ?? 0);
$draw  = intval($_POST['draw'] ?? 1);

/* ================= TOTAL DATA ================= */
$totalData = $conn
    ->query("SELECT COUNT(*) FROM production_50")
    ->fetch_row()[0];

/* ================= MAIN QUERY ================= */
$q = $conn->query("
    SELECT *
    FROM production_50
    ORDER BY id DESC
    LIMIT $start, $limit
");

/* ================= FORMAT DATA ================= */
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
               onclick="return confirm(\'Hapus data?\')"
               class="btn btn-danger btn-sm">
               Hapus
            </a>
        '
    ];
}

/* ================= OUTPUT ================= */
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalData,
    "recordsFiltered" => $totalData,
    "data"            => $data
]);
