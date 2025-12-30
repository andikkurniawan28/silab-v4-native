<?php
include('db_packer.php');

/* kolom DataTables */
$columns = ['id', 'bruto', 'tarra', 'netto', 'created_at'];

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

/* kunci paging di window */
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

$where = "WHERE line = 'rs_out'";

if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $where .= " AND (
        id LIKE '%$safe%' OR
        bruto LIKE '%$safe%' OR
        netto LIKE '%$safe%'
    )";
}

/* ===============================
   TOTAL (dibatasi window)
================================= */
$totalData = min(
    $window,
    (int)$conn->query("
        SELECT COUNT(*) 
        FROM in_process_weighings
        WHERE line = 'rs_out'
    ")->fetch_row()[0]
);

$totalFiltered = $totalData;
if ($search !== '') {
    $totalFiltered = min(
        $window,
        (int)$conn->query("
            SELECT COUNT(*)
            FROM in_process_weighings
            $where
        ")->fetch_row()[0]
    );
}

/* ===============================
   DATA QUERY (🔥 MAKS 1000 🔥)
================================= */
$sql = "
    SELECT *
    FROM in_process_weighings
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
            <a href="timbangan_rs_out_edit.php?id='.$row['id'].'"
               class="btn btn-warning btn-sm">Edit</a>
            <a href="timbangan_rs_out_delete.php?id='.$row['id'].'"
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
