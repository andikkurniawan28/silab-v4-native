<?php
include('db.php');

$columns = [
    'a.id',
    'm.name'
];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']] ?? 'a.id';
$dir    = $_POST['order'][0]['dir'] ?? 'desc';
$search = $_POST['search']['value'];

$where = "WHERE a.is_verified = 0";
if ($search) {
    $where .= " AND (a.id LIKE '%$search%' OR m.name LIKE '%$search%')";
}

$totalData = $conn->query("
    SELECT COUNT(*) 
    FROM analisa_off_farm_new
    WHERE is_verified = 0
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

    $indQ = $conn->query("
        SELECT i.name
        FROM methods md
        JOIN indicators i ON i.id=md.indicator_id
        WHERE md.material_id='{$row['material_id']}'
    ");

    $hasil = '<ul class="mb-0 pl-3">';
    while ($ind = $indQ->fetch_assoc()) {
        $col = ucwords(str_replace(' ', '_', $ind['name']));
        $value = $row[$col] ?? '-';
        $hasil .= "<li>{$ind['name']} : {$value}</li>";
    }
    $hasil .= '</ul>';

    $data[] = [
        'check' => '<input type="checkbox" class="row-check" name="ids[]" value="'.$row['id'].'">',
        'id' => $row['id'],
        'material' => $row['material'],
        'hasil_analisa' => $hasil
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
