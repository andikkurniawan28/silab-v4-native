<?php
include('db.php');

$columns = [
    'm.id',
    's.name',
    'm.name',
    'indicators'
];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']];
$dir    = $_POST['order'][0]['dir'];
$search = $_POST['search']['value'];

$where = '';
if ($search) {
    $where = "
        WHERE 
            m.name LIKE '%$search%' 
            OR s.name LIKE '%$search%'
            OR i.name LIKE '%$search%'
    ";
}

/* total data */
$totalData = $conn->query("
    SELECT COUNT(*) FROM materials
")->fetch_row()[0];

/* total filtered */
$totalFiltered = $conn->query("
    SELECT COUNT(DISTINCT m.id)
    FROM materials m
    JOIN stations s ON s.id = m.station_id
    LEFT JOIN methods me ON me.material_id = m.id
    LEFT JOIN indicators i ON i.id = me.indicator_id
    $where
")->fetch_row()[0];

/* data */
$sql = "
    SELECT 
        m.id,
        m.name,
        s.name AS station,
        GROUP_CONCAT(i.name ORDER BY i.name SEPARATOR ', ') AS indicators
    FROM materials m
    JOIN stations s ON s.id = m.station_id
    LEFT JOIN methods me ON me.material_id = m.id
    LEFT JOIN indicators i ON i.id = me.indicator_id
    $where
    GROUP BY m.id
    ORDER BY $order $dir
    LIMIT $start,$limit
";

$q = $conn->query($sql);

$data = [];
while($r = $q->fetch_assoc()){
    $data[] = [
        'id' => $r['id'],
        'station' => $r['station'],
        'name' => $r['name'],
        'indicators' => $r['indicators'] ?: '-',
        'action' => '
            <a href="material_edit.php?id='.$r['id'].'"
               class="btn btn-warning btn-sm">
               Edit
            </a>
            <a href="material_delete.php?id='.$r['id'].'"
                class="btn btn-danger btn-sm"
                onclick="return confirm(\'Hapus material ini?\')">
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
