<?php
include('db.php');

$columns = ['id', 'code'];

$limit  = intval($_POST['length'] ?? 10);
$start  = intval($_POST['start'] ?? 0);
$order  = $columns[$_POST['order'][0]['column'] ?? 0];
$dir    = $_POST['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
$search = $conn->real_escape_string($_POST['search']['value'] ?? '');

/* BASE SUBQUERY — hanya 1000 data terakhir */
$baseTable = "
    (
        SELECT *
        FROM analisa_pendahuluans
        ORDER BY id DESC
        LIMIT 1000
    ) AS t
";

/* WHERE */
$where = "WHERE 1=1";
if ($search !== '') {
    $where .= " AND code LIKE '%$search%'";
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
        'code' => $row['code'],
        'brix_atas' => $row['brix_atas'],
        'pol_atas' => $row['pol_atas'],
        'rendemen_atas' => $row['rendemen_atas'],
        'brix_tengah' => $row['brix_tengah'],
        'pol_tengah' => $row['pol_tengah'],
        'rendemen_tengah' => $row['rendemen_tengah'],
        'brix_bawah' => $row['brix_bawah'],
        'pol_bawah' => $row['pol_bawah'],
        'rendemen_bawah' => $row['rendemen_bawah'],
        'timestamp' => $row['created_at'],
        'action' => '
            <a href="analisa_pendahuluan_edit.php?id='.$row['id'].'" 
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
