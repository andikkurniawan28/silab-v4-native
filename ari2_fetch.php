<?php
include('db.php');

$columns = [
    'id',              // 0
    'kartu_ari',       // 1
    'ari_at',          // 2 ← timestamp
    'nomor_antrian',   // 3
    'nopol',   // 3
    'brix_ari',        // 4
    'pol_ari',         // 5
    'pol_baca_ari',    // 6
    'rendemen_ari',    // 7
    'id'               // 8 action (dummy)
];

$limit = intval($_POST['length'] ?? 10);
$start = intval($_POST['start'] ?? 0);

$orderIndex = intval($_POST['order'][0]['column'] ?? 0);
$order = $columns[$orderIndex] ?? 'ari_at';

$dir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc'
    ? 'ASC'
    : 'DESC';

$search = $conn->real_escape_string($_POST['search']['value'] ?? '');

$baseTable = "
(
    SELECT *
    FROM analisa_on_farms
    WHERE kartu_ari IS NOT NULL
    ORDER BY ari_at DESC
    LIMIT 1000
) t
";

$where = "WHERE 1=1";

if ($search !== '') {
    $where .= "
        AND (
            nomor_antrian LIKE '%$search%'
            OR id LIKE '%$search%'
            OR kartu_ari LIKE '%$search%'
        )
    ";
}

$totalData = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
")->fetch_row()[0];

$totalFiltered = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
    $where
")->fetch_row()[0];

$sql = "
SELECT *
FROM $baseTable
$where
ORDER BY $order $dir
LIMIT $start, $limit
";

$query = $conn->query($sql);

$data = [];

while ($row = $query->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'gelas' => $row['kartu_ari'],
        'timestamp' => $row['ari_at'],
        'nomor_antrian' => $row['nomor_antrian'],
        'nopol' => $row['nopol'],
        'brix_ari' => $row['brix_ari'],
        'pol_ari' => $row['pol_ari'],
        'pol_baca_ari' => $row['pol_baca_ari'],
        'rendemen_ari' => $row['rendemen_ari'],
        'action' => '
            <a href="ari2_edit.php?id='.$row['id'].'"
               class="btn btn-warning btn-sm">
               Edit
            </a>'
    ];
}

header('Content-Type: application/json');

echo json_encode([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);