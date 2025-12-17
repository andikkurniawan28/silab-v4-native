<?php
include('db.php');

$columns = ['id','name'];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']];
$dir    = $_POST['order'][0]['dir'];
$search = $_POST['search']['value'];

$where = '';
if (!empty($search)) {
    $where = "WHERE name LIKE '%$search%'";
}

/* total */
$totalData = $conn->query("
    SELECT COUNT(*) FROM stations
")->fetch_row()[0];

/* filtered */
$totalFiltered = $conn->query("
    SELECT COUNT(*) FROM stations $where
")->fetch_row()[0];

/* data */
$sql = "
    SELECT * FROM stations
    $where
    ORDER BY $order $dir
    LIMIT $start,$limit
";

$query = $conn->query($sql);

$data = [];

while ($row = $query->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'action' => '
            <a href="station_edit.php?id='.$row['id'].'" 
               class="btn btn-warning btn-sm">
                Edit
            </a>
            <button class="btn btn-danger btn-sm"
                onclick="deleteStation('.$row['id'].')">
                Hapus
            </button>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
