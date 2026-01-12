<?php
include('db.php');

$columns = [
    'a.id',
    'm.name',
    'u.name',
    'a.created_at'
];

$limit  = intval($_POST['length'] ?? 10);
$start  = intval($_POST['start'] ?? 0);
$order  = $columns[$_POST['order'][0]['column'] ?? 0] ?? 'a.id';
$dir    = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$search = $conn->real_escape_string($_POST['search']['value'] ?? '');

/* ===============================
   BASE TABLE — LIMIT 1000 TERAKHIR
   =============================== */
$baseTable = "
    (
        SELECT *
        FROM analisa_off_farm_new
        ORDER BY id DESC
        LIMIT 1000
    ) AS a
";

/* WHERE */
$where = "WHERE 1=1";
if ($search !== '') {
    $where .= "
        AND (
            m.name LIKE '%$search%' OR
            u.name LIKE '%$search%' OR
            a.id LIKE '%$search%'
        )
    ";
}

/* TOTAL DATA (maks 1000) */
$totalData = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
")->fetch_row()[0];

/* TOTAL FILTERED */
$totalFiltered = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
    JOIN materials m ON m.id = a.material_id
    JOIN users u ON u.id = a.user_id
    $where
")->fetch_row()[0];

/* DATA */
$sql = "
    SELECT a.*,
           m.name AS material,
           u.name AS user
    FROM $baseTable
    JOIN materials m ON m.id = a.material_id
    JOIN users u ON u.id = a.user_id
    $where
    ORDER BY $order $dir
    LIMIT $start, $limit
";

$q = $conn->query($sql);
$data = [];

while ($r = $q->fetch_assoc()) {
    $data[] = [
        'id' => $r['id'],
        'material' => $r['material'],
        'user' => $r['user'],
        'created_at' => $r['created_at'],
        'timestamp_riil' => $r['timestamp_riil'],
        'action' => '
            <a href="cetak_barcode_show.php?id='.$r['id'].'"
               target="_blank"
               class="btn btn-info btn-sm mb-1">Cetak</a>

            <a href="edit_material_barcode.php?id='.$r['id'].'"
               class="btn btn-warning btn-sm mb-1">Edit Material</a>

            <a href="edit_timestamp_barcode.php?id='.$r['id'].'"
               class="btn btn-secondary btn-sm mb-1">Edit Timestamp</a>

            <a href="barcode_delete.php?id='.$r['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus barcode ini?\')">
                Hapus
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
