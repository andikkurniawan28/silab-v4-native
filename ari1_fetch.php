<?php
include('db.php');

$columns = ['id', 'nomor_antrian'];

$limit  = intval($_POST['length'] ?? 10);
$start  = intval($_POST['start'] ?? 0);
$order  = $columns[$_POST['order'][0]['column'] ?? 0];
$dir    = $_POST['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
$search = $conn->real_escape_string($_POST['search']['value'] ?? '');

/* BASE SUBQUERY — hanya 1000 data terakhir */
$baseTable = "
    (
        SELECT *
        FROM analisa_on_farms
        WHERE kartu_core IS NOT NULL
        ORDER BY id DESC
        LIMIT 1000
    ) AS t
";

/* WHERE */
$where = "WHERE 1=1";
if ($search !== '') {
    $where .= " AND nomor_antrian LIKE '%$search%' OR id LIKE '%$search%' OR kartu_core LIKE '%$search%'";
}

/* total data (max 1000) */
$totalData = $conn->query("
    SELECT COUNT(*) FROM $baseTable
")->fetch_row()[0];

/* total filtered */
$totalFiltered = $conn->query("
    SELECT COUNT(*) FROM $baseTable $where
")->fetch_row()[0];

/* data */
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
        'gelas' => $row['kartu_core'],
        'nomor_antrian' => $row['nomor_antrian'],
        'brix_core' => $row['brix_core'],
        'pol_core' => $row['pol_core'],
        'pol_baca_core' => $row['pol_baca_core'],
        'rendemen_core' => $row['rendemen_core'],
        'timestamp' => $row['core_at'],
        'action' => '
            <a href="ari1_edit.php?id='.$row['id'].'" 
               class="btn btn-warning btn-sm">
                Edit
            </a>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
