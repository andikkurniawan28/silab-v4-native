<?php
include('db_packer.php');

/* kolom DataTables */
$columns = ['id', 'value', 'created_at'];

/* ===============================
   WINDOW CONFIG
================================= */
$window = 1000;

/* paging dari DataTables */
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;

/* sanitasi */
$start  = max(0, (int)$start);
$length = max(1, (int)$length);

/* kunci paging di window 1000 */
if ($start >= $window) {
    $start = max(0, $window - $length);
}

$limit = min($length, $window - $start);

/* ===============================
   ORDER (paksa id desc)
================================= */
$order = 'id';
$dir   = 'desc';

/* ===============================
   SEARCH
================================= */
$search = $_POST['search']['value'] ?? '';

$where = '';
if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $where = "WHERE id LIKE '%$safe%'";
}

/* ===============================
   TOTAL (untuk DataTables info)
   dibatasi window 1000
================================= */
$totalData = min(
    $window,
    (int)$conn->query("SELECT COUNT(*) FROM weighing_test")->fetch_row()[0]
);

$totalFiltered = $totalData;
if ($where !== '') {
    $totalFiltered = min(
        $window,
        (int)$conn->query("SELECT COUNT(*) FROM weighing_test $where")->fetch_row()[0]
    );
}

/* ===============================
   DATA QUERY (🔥 MAKS 1000 🔥)
================================= */
$sql = "
    SELECT *
    FROM weighing_test
    $where
    ORDER BY id DESC
    LIMIT $start, $limit
";

$query = $conn->query($sql);

$data = [];

while ($row = $query->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'value' => $row['value'],
        'created_at' => $row['created_at'],
        'action' => '
            <a href="timbangan_rs_in_edit.php?id='.$row['id'].'"
               class="btn btn-warning btn-sm">Edit</a>
            <a href="timbangan_rs_in_delete.php?id='.$row['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data ini?\')">Hapus</a>
        '
    ];
}

/* ===============================
   RESPONSE
================================= */
echo json_encode([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
