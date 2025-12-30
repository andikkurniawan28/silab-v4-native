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
$chemicalQuery = $conn->query("SELECT id, name FROM chemicals ORDER BY id");
$chemicals = [];
$selectColumns = ['k.id', 'k.created_at'];

while ($k = $chemicalQuery->fetch_assoc()) {
    $col = 'p' . $k['id'];
    $chemicals[$col] = $k['name'];
    $selectColumns[] = "k.`$col`";
}

/* =========================================
   ORDERING
========================================= */
$columns = ['id', 'created_at', 'hasil'];
$orderColumnIndex = intval($order['column'] ?? 1);
$orderDir = ($order['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

$orderColumn = 'k.created_at';
if ($orderColumnIndex === 0) {
    $orderColumn = 'k.id';
}

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

    foreach (array_keys($chemicals) as $col) {
        $whereParts[] = "k.`$col` LIKE '%$searchEsc%'";
    }

    $where = "WHERE " . implode(' OR ', $whereParts);
}

/* =========================================
   TOTAL DATA
========================================= */
$totalData = $conn->query("SELECT COUNT(*) FROM penggunaan_bpp")->fetch_row()[0];
$totalFiltered = $conn->query("SELECT COUNT(*) FROM penggunaan_bpp k $where")->fetch_row()[0];

/* =========================================
   QUERY DATA
========================================= */
$selectSql = implode(', ', $selectColumns);
$sql = "
    SELECT $selectSql
    FROM penggunaan_bpp k
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

    foreach ($chemicals as $col => $name) {
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
            <a href="penggunaan_bpp_edit.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
            <a href="penggunaan_bpp_delete.php?id=' . $row['id'] . '"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data ini?\')">Hapus</a>
        '
    ];
}

/* =========================================
   OUTPUT JSON
========================================= */
echo json_encode([
    'draw' => $draw,
    'recordsTotal' => intval($totalData),
    'recordsFiltered' => intval($totalFiltered),
    'data' => $data
]);
