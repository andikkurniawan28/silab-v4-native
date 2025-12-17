<?php
include('db.php');

$columns = ['id','name'];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']] ?? 'id';
$dir    = $_POST['order'][0]['dir'] ?? 'asc';
$search = $_POST['search']['value'];

$where = '';
if (!empty($search)) {
    $where = "WHERE name LIKE '%$search%'";
}

/* total */
$totalData = $conn->query("
    SELECT COUNT(*) FROM roles
")->fetch_row()[0];

/* filtered */
$totalFiltered = $conn->query("
    SELECT COUNT(*) FROM roles $where
")->fetch_row()[0];

/* data */
$sql = "
    SELECT * FROM roles
    $where
    ORDER BY $order $dir
    LIMIT $start,$limit
";

$query = $conn->query($sql);
$data = [];

while ($row = $query->fetch_assoc()) {

    /**
     * Ambil fitur per role
     */
    $featQ = $conn->query("
        SELECT f.judul
        FROM permissions p
        JOIN features f ON f.id = p.feature_id
        WHERE p.role_id = {$row['id']}
        ORDER BY f.judul ASC
    ");

    $fitur = '<ul class="mb-0 pl-3">';

    if ($featQ->num_rows > 0) {
        while ($f = $featQ->fetch_assoc()) {
            $fitur .= '<li>'.htmlspecialchars($f['judul']).'</li>';
        }
    } else {
        $fitur .= '<li><em>-</em></li>';
    }

    $fitur .= '</ul>';

    $data[] = [
        'id'    => $row['id'],
        'name'  => $row['name'],
        'fitur' => $fitur,
        'action' => '
            <a href="role_edit.php?id='.$row['id'].'"
               class="btn btn-warning btn-sm">
                Edit
            </a>
            <button class="btn btn-danger btn-sm"
                onclick="deleteRole('.$row['id'].')">
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
