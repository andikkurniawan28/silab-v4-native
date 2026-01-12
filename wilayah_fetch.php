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
    SELECT COUNT(*) FROM kuds
")->fetch_row()[0];

/* filtered */
$totalFiltered = $conn->query("
    SELECT COUNT(*) FROM kuds $where
")->fetch_row()[0];

/* data */
$sql = "
    SELECT * FROM kuds
    $where
    ORDER BY $order $dir
    LIMIT $start,$limit
";

$query = $conn->query($sql);

$data = [];

while ($row = $query->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'code' => $row['code'],
        'name' => $row['name'],
        'action' => '
            <a href="wilayah_edit.php?id='.$row['id'].'" 
               class="btn btn-warning btn-sm">
                Edit
            </a>
            <a href="wilayah_delete.php?id='.$row['id'].'"
                class="btn btn-danger btn-sm"
                onclick="return confirm(\'Hapus wilayah ini?\')">
                    Hapus
            </a>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
