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
        WHERE kartu_ari IS NOT NULL
        ORDER BY id DESC
        LIMIT 1000
    ) AS t
";

/* WHERE */
$where = "WHERE 1=1";
if ($search !== '') {
    $where .= " AND nomor_antrian LIKE '%$search%' OR id LIKE '%$search%' OR kartu_ari LIKE '%$search%'";
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
        'gelas' => $row['kartu_ari'],
        'nomor_antrian' => $row['nomor_antrian'],
        'brix_ari' => $row['brix_ari'],
        'pol_ari' => $row['pol_ari'],
        'pol_baca_ari' => $row['pol_baca_ari'],
        'rendemen_ari' => $row['rendemen_ari'],
        'timestamp' => $row['ari_at'],
        'action' => '
            <a href="ari2_edit.php?id='.$row['id'].'" 
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
