<?php
include('db.php');

$columns = [
    'a.id',
    'm.name',
    'a.is_verified'
];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']] ?? 'a.id';
$dir    = $_POST['order'][0]['dir'] ?? 'desc';
$search = $_POST['search']['value'];

$where = '';
if ($search) {
    $where = "WHERE a.id LIKE '%$search%' OR m.name LIKE '%$search%'";
}

$totalData = $conn->query("
    SELECT COUNT(*) FROM analisa_off_farm_new
")->fetch_row()[0];

$totalFiltered = $conn->query("
    SELECT COUNT(*)
    FROM analisa_off_farm_new a
    JOIN materials m ON m.id=a.material_id
    $where
")->fetch_row()[0];

$sql = "
    SELECT a.*, m.name AS material
    FROM analisa_off_farm_new a
    JOIN materials m ON m.id=a.material_id
    $where
    ORDER BY a.id DESC
    LIMIT $start,$limit
";

$q = $conn->query($sql);
$data = [];

while ($row = $q->fetch_assoc()) {

    // Ambil indikator berdasarkan material
    $indQ = $conn->query("
        SELECT i.name
        FROM methods md
        JOIN indicators i ON i.id=md.indicator_id
        WHERE md.material_id='{$row['material_id']}'
    ");

    $hasil = '<ul class="mb-0 pl-3">';
    while ($ind = $indQ->fetch_assoc()) {
        $col = ucwords(str_replace(' ', '_', $ind['name']));
        $value = isset($row[$col]) && $row[$col] !== ''
            ? $row[$col]
            : '-';

        $hasil .= "<li>{$ind['name']} : {$value}</li>";
    }
    $hasil .= '</ul>';

    // Status badge
    if ($row['is_verified'] == 1) {
        $statusBadge = '<span class="badge badge-success">Sudah diverifikasi</span>';
    } else {
        $statusBadge = '<span class="badge badge-dark text-white">Belum diverifikasi</span>';
    }

    $data[] = [
        'id' => $row['id'],
        'material' => $row['material'],
        'hasil_analisa' => $hasil,
        'status' => $statusBadge,
        'action' => '
            <a href="analisa_edit.php?id='.$row['id'].'"
               class="btn btn-success btn-sm">Edit</a>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
