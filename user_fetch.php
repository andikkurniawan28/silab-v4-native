<?php
include('db.php');

$columns = [
    'users.id',
    'roles.name',
    'users.name',
    'users.username',
    'users.is_active'
];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']];
$dir    = $_POST['order'][0]['dir'];
$search = $_POST['search']['value'];

$where = '';
if (!empty($search)) {
    $where = "WHERE 
        users.name LIKE '%$search%' 
        OR users.username LIKE '%$search%' 
        OR roles.name LIKE '%$search%'";
}

/* total data */
$totalData = $conn->query("
    SELECT COUNT(*) 
    FROM users
")->fetch_row()[0];

/* total filtered */
$totalFiltered = $conn->query("
    SELECT COUNT(*) 
    FROM users
    LEFT JOIN roles ON roles.id = users.role_id
    $where
")->fetch_row()[0];

/* data utama */
$sql = "
    SELECT
        users.id,
        users.name,
        users.username,
        roles.name AS role,
        users.is_active
    FROM users
    LEFT JOIN roles ON roles.id = users.role_id
    $where
    ORDER BY $order $dir
    LIMIT $start,$limit
";

$query = $conn->query($sql);

$data = [];

while ($row = $query->fetch_assoc()) {

    $status = $row['is_active']
        ? '<span class="badge badge-success">Aktif</span>'
        : '<span class="badge badge-secondary">Nonaktif</span>';

    $data[] = [
        'id' => $row['id'],
        'role' => $row['role'],
        'name' => $row['name'],
        'username' => $row['username'],
        'is_active' => $status,
        'action' => '
            <a href="user_edit.php?id='.$row['id'].'" 
               class="btn btn-warning btn-sm">
                Edit
            </a>
            <button class="btn btn-danger btn-sm"
                onclick="deleteUser('.$row['id'].')">
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
