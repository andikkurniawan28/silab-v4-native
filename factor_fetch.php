<?php
include('db.php');

$columns = ['id','name','value'];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']];
$dir    = $_POST['order'][0]['dir'];
$search = $_POST['search']['value'];

$where = '';
if (!empty($search)) {
    $where = "WHERE name LIKE '%$search%'";
}

$totalData = $conn->query("
    SELECT COUNT(*) FROM factors
")->fetch_row()[0];

$totalFiltered = $conn->query("
    SELECT COUNT(*) FROM factors $where
")->fetch_row()[0];

$sql = "
    SELECT * FROM factors
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
        'value' => $row['value'],
        'action' => '
            <a href="factor_edit.php?id='.$row['id'].'"
               class="btn btn-warning btn-sm">
                Edit
            </a>
            <a href="factor_delete.php?id='.$row['id'].'"
                class="btn btn-danger btn-sm"
                onclick="return confirm(\'Hapus faktor ini?\')">
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
