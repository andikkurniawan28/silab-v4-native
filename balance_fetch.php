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
        FROM balances
        ORDER BY id DESC
        LIMIT 1000
    ) AS b
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
