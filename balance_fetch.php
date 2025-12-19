<?php
include('db.php');

$limit = intval($_POST['length'] ?? 10);
$start = intval($_POST['start'] ?? 0);

$totalData = $conn->query("SELECT COUNT(*) FROM balances")->fetch_row()[0];

$q = $conn->query("
    SELECT *
    FROM balances
    ORDER BY id DESC
    LIMIT $start,$limit
");

$data = [];
while($r = $q->fetch_assoc()){
    $data[] = [
        'id' => $r['id'],
        'created_at' => date('d-m-Y H:i', strtotime($r['created_at'])),
        'tebu' => number_format($r['tebu'], 2),
        'totalizer' => number_format($r['totalizer']),
        'totalizer_gilingan' => number_format($r['totalizer_gilingan']),
        'totalizer_d1' => number_format($r['totalizer_decanter_1st']),
        'totalizer_d2' => number_format($r['totalizer_decanter_2nd']),
        'flow_nm' => $r['flow_nm'],
        'flow_nm_gilingan' => $r['flow_nm_gilingan'],
        'flow_decanter_1st' => $r['flow_decanter_1st'],
        'flow_decanter_2nd' => $r['flow_decanter_2nd'],
        'nm_persen_tebu' => $r['nm_persen_tebu'],
        'nm_persen_tebu_gilingan' => $r['nm_persen_tebu_gilingan'],
        'sfc' => $r['sfc'],
        'action' => '
            <a href="balance_delete.php?id='.$r['id'].'"
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
