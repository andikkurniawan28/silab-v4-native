<?php
include('db.php');

/* kolom */
$columns = ['id', 'bruto', 'tarra', 'netto', 'created_at'];

/* WINDOW SIZE */
$window = 1000;

/* paging */
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;

/* sanitasi */
$start  = max(0, (int)$start);
$length = max(1, (int)$length);

/* kunci ke window 1000 */
if ($start >= $window) {
    $start = max(0, $window - $length);
}

$limit = min($length, $window - $start);

/* search */
$search = $_POST['search']['value'] ?? '';

$where = '';
if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $where = "WHERE id LIKE '%$safe%'";
}

/* total data → untuk DataTables info */
$totalData = min(
    1000,
    (int)$conn->query("SELECT COUNT(*) FROM mollases")->fetch_row()[0]
);

/* total filtered */
$totalFiltered = $totalData;
if ($where !== '') {
    $totalFiltered = min(
        1000,
        (int)$conn->query("SELECT COUNT(*) FROM mollases $where")->fetch_row()[0]
    );
}

/* DATA QUERY (🔥 CEPAT 🔥) */
$sql = "
    SELECT *
    FROM mollases
    $where
    ORDER BY id DESC
    LIMIT $start, $limit
";

$query = $conn->query($sql);

$data = [];

while ($row = $query->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'bruto' => $row['bruto'],
        'tarra' => $row['tarra'],
        'netto' => $row['netto'],
        'created_at' => $row['created_at'],
        'action' => '
            <a href="timbangan_tetes_edit.php?id='.$row['id'].'"
               class="btn btn-warning btn-sm">Edit</a>
            <a href="timbangan_tetes_delete.php?id='.$row['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus mollase ini?\')">Hapus</a>
        '
    ];
}

/* response */
echo json_encode([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
