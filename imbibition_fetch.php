<?php
include('db.php');

$limit = intval($_POST['length'] ?? 10);
$start = intval($_POST['start'] ?? 0);

$totalData = $conn->query("SELECT COUNT(*) FROM imbibitions")->fetch_row()[0];

$q = $conn->query("
    SELECT *
    FROM imbibitions
    ORDER BY id DESC
    LIMIT $start,$limit
");

$data = [];
while($r = $q->fetch_assoc()){
    $data[] = [
        'id' => $r['id'],
        'created_at' => date('d-m-Y H:i', strtotime($r['created_at'])),
        'totalizer' => number_format($r['totalizer']),
        'flow_imb' => $r['flow_imb'],
        'action' => '
            <a href="imbibition_delete.php?id='.$r['id'].'"
               onclick="return confirm(\'Hapus data?\')"
               class="btn btn-danger btn-sm">Hapus</a>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalData,
    "data" => $data
]);
