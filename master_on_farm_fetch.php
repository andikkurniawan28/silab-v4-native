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
        ORDER BY id DESC
    ) AS t
";

/* WHERE */
$where = "WHERE 1=1";
if ($search !== '') {
    $where .= " AND id LIKE '%$search%' OR spta LIKE '%$search%' OR nomor_antrian LIKE '%$search%' OR register LIKE '%$search%' OR petani LIKE '%$search%' OR nopol LIKE '%$search%' OR mutu_tebu LIKE '%$search%'";
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
        'spta' => $row['spta'],
        'nomor_antrian' => $row['nomor_antrian'],
        'register' => $row['register'],
        'petani' => $row['petani'],
        'nopol' => $row['nopol'],
        'rendemen_ari' => $row['rendemen_ari'],
        'mutu_tebu' => $row['mutu_tebu'],
        'bobot_tebu' => $row['bobot_tebu'],
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
