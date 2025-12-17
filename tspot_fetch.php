<?php
include('db.php');

$columns = ['id','name'];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']];
$dir    = $_POST['order'][0]['dir'];
$search = $_POST['search']['value'];

$where = '';
if ($search) {
    $where = "WHERE name LIKE '%$search%'";
}

$totalData = $conn->query("
    SELECT COUNT(*) FROM tspots
")->fetch_row()[0];

$totalFiltered = $conn->query("
    SELECT COUNT(*) FROM tspots $where
")->fetch_row()[0];

$sql = "
    SELECT * FROM tspots
    $where
    ORDER BY $order $dir
    LIMIT $start,$limit
";

$q = $conn->query($sql);

$data = [];
while($r = $q->fetch_assoc()){
    $data[] = [
        'id' => $r['id'],
        'name' => $r['name'],
        'action' => '
            <a href="tspot_edit.php?id='.$r['id'].'"
               class="btn btn-warning btn-sm">Edit</a>
            <button class="btn btn-danger btn-sm"
                onclick="deletetspot('.$r['id'].')">Hapus</button>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
