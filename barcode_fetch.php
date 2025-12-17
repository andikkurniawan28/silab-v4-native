<?php
include('db.php');

$columns = [
    'a.id',
    'm.name',
    'u.name',
    'a.created_at'
];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']];
$dir    = $_POST['order'][0]['dir'];
$search = $_POST['search']['value'];

$where = '';
if ($search) {
    $where = "WHERE m.name LIKE '%$search%'
              OR u.name LIKE '%$search%'
              OR a.id LIKE '%$search%'";
}

$totalData = $conn->query("
    SELECT COUNT(*) FROM analisa_off_farm_new
")->fetch_row()[0];

$totalFiltered = $conn->query("
    SELECT COUNT(*)
    FROM analisa_off_farm_new a
    JOIN materials m ON m.id=a.material_id
    JOIN users u ON u.id=a.user_id
    $where
")->fetch_row()[0];

$sql = "
    SELECT a.*,
           m.name AS material,
           u.name AS user
    FROM analisa_off_farm_new a
    JOIN materials m ON m.id=a.material_id
    JOIN users u ON u.id=a.user_id
    $where
    ORDER BY $order $dir
    LIMIT $start,$limit
";

$q = $conn->query($sql);
$data = [];

while($r = $q->fetch_assoc()){
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

            <button class="btn btn-danger btn-sm"
                onclick="deleteBarcode('.$r['id'].')">Hapus</button>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
