<?php
include('db.php');

$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$limit  = intval($_POST['length'] ?? 10);
$order  = $_POST['order'][0] ?? [];
$search = $_POST['search']['value'] ?? '';

/* =========================================
   ORDERING
========================================= */
$orderColumnIndex = intval($order['column'] ?? 0);
$orderDir = ($order['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

$orderColumn = 'm.created_at';
if ($orderColumnIndex === 0) {
    $orderColumn = 'm.id';
}

/* =========================================
   BASE TABLE — 1000 DATA TERAKHIR
========================================= */
$baseTable = "
    (
        SELECT *
        FROM mollases
        ORDER BY id DESC
        LIMIT 1000
    ) AS m
";

/* =========================================
   SEARCH
========================================= */
$where = '';
if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $where = "
        WHERE
            m.id LIKE '%$safe%' OR
            m.bruto LIKE '%$safe%' OR
            m.tarra LIKE '%$safe%' OR
            m.netto LIKE '%$safe%' OR
            DATE_FORMAT(m.created_at, '%d-%m-%Y %H:%i:%s') LIKE '%$safe%'
    ";
}

/* =========================================
   TOTAL DATA (maks 1000)
========================================= */
$totalData = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
")->fetch_row()[0];

$totalFiltered = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
    $where
")->fetch_row()[0];

/* =========================================
   QUERY DATA
========================================= */
$sql = "
    SELECT *
    FROM $baseTable
    $where
    ORDER BY $orderColumn $orderDir
    LIMIT $start, $limit
";

$query = $conn->query($sql);
$data = [];

/* =========================================
   BUILD RESPONSE
========================================= */
while ($row = $query->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'bruto' => $row['bruto'],
        'tarra' => $row['tarra'],
        'netto' => $row['netto'],
        'created_at' => date('d-m-Y H:i:s', strtotime($row['created_at'])),
        'action' => '
            <a href="timbangan_tetes_edit.php?id='.$row['id'].'"
               class="btn btn-warning btn-sm">Edit</a>
            <a href="timbangan_tetes_delete.php?id='.$row['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus mollase ini?\')">
               Hapus
            </a>
        '
    ];
}

/* =========================================
   OUTPUT JSON
========================================= */
echo json_encode([
    'draw' => $draw,
    'recordsTotal' => $totalData,
    'recordsFiltered' => $totalFiltered,
    'data' => $data
]);
