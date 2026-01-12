<?php
include('db.php');

$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$limit  = intval($_POST['length'] ?? 10);
$order  = $_POST['order'][0] ?? [];
$search = $_POST['search']['value'] ?? '';

/* =========================================
   AMBIL KSPOT (mapping kolom -> nama)
========================================= */
$kspotQuery = $conn->query("SELECT id, name FROM kspots ORDER BY id");
$kspots = [];
$selectColumns = ['k.id', 'k.created_at'];

while ($k = $kspotQuery->fetch_assoc()) {
    $col = 'p' . $k['id'];
    $kspots[$col] = $k['name'];
    $selectColumns[] = "k.`$col`";
}

/* =========================================
   ORDERING
========================================= */
$orderColumnIndex = intval($order['column'] ?? 1);
$orderDir = ($order['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

$orderColumn = 'k.created_at';
if ($orderColumnIndex === 0) {
    $orderColumn = 'k.id';
}

/* =========================================
   BASE TABLE — 1000 DATA TERAKHIR
========================================= */
$baseTable = "
    (
        SELECT *
        FROM keliling_proses
        ORDER BY id DESC
        LIMIT 1000
    ) AS k
";

/* =========================================
   SEARCH
========================================= */
$where = '';
if ($search !== '') {
    $searchEsc = $conn->real_escape_string($search);

    $whereParts = [
        "k.id LIKE '%$searchEsc%'",
        "DATE_FORMAT(k.created_at, '%d-%m-%Y %H:%i:%s') LIKE '%$searchEsc%'"
    ];

    foreach (array_keys($kspots) as $col) {
        $whereParts[] = "k.`$col` LIKE '%$searchEsc%'";
    }

    $where = "WHERE " . implode(' OR ', $whereParts);
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
$selectSql = implode(', ', $selectColumns);

$sql = "
    SELECT $selectSql
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

    $hasilItems = [];

    foreach ($kspots as $col => $name) {
        if ($row[$col] !== null && $row[$col] !== '') {
            $hasilItems[] =
                '<li><strong>' .
                htmlspecialchars($name) .
                '</strong> : ' .
                htmlspecialchars($row[$col]) .
                '</li>';
        }
    }

    $hasilHtml = empty($hasilItems)
        ? '-'
        : '<ul class="mb-0">' . implode('', $hasilItems) . '</ul>';

    $data[] = [
        'id' => $row['id'],
        'created_at' => date('d-m-Y H:i:s', strtotime($row['created_at'])),
        'hasil' => $hasilHtml,
        'action' => '
            <a href="keliling_proses_edit.php?id='.$row['id'].'" 
               class="btn btn-warning btn-sm">Edit</a>
            <a href="keliling_proses_delete.php?id='.$row['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data ini?\')">
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
