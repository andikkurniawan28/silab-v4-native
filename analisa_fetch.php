<?php
include('db.php');

$columns = [
    'a.id',
    'm.name',
    'a.is_verified'
];

$limit  = intval($_POST['length'] ?? 10);
$start  = intval($_POST['start'] ?? 0);
$order  = $columns[$_POST['order'][0]['column'] ?? 0] ?? 'a.id';
$dir    = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$search = $conn->real_escape_string($_POST['search']['value'] ?? '');

/* ===============================
   BASE TABLE (LIMIT 1000 TERAKHIR)
   =============================== */
$baseTable = "
    (
        SELECT *
        FROM analisa_off_farm_new
        ORDER BY id DESC
        LIMIT 1000
    ) AS a
";

/* WHERE */
$where = "WHERE 1=1";
if ($search !== '') {
    $where .= " AND (a.id LIKE '%$search%' OR m.name LIKE '%$search%')";
}

/* TOTAL DATA (maks 1000) */
$totalData = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
")->fetch_row()[0];

/* TOTAL FILTERED */
$totalFiltered = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
    JOIN materials m ON m.id = a.material_id
    $where
")->fetch_row()[0];

/* DATA QUERY */
$sql = "
    SELECT a.*, m.name AS material, u.name AS user
    FROM $baseTable
    JOIN materials m ON m.id = a.material_id
    LEFT JOIN users u ON u.id = a.user_id
    $where
    ORDER BY $order $dir
    LIMIT $start, $limit
";

$q = $conn->query($sql);

$data = [];

while ($row = $q->fetch_assoc()) {

    /* ambil indikator per material */
    $indQ = $conn->query("
        SELECT i.name
        FROM methods md
        JOIN indicators i ON i.id = md.indicator_id
        WHERE md.material_id = '{$row['material_id']}'
    ");

    $hasil = '<ul class="mb-0 pl-3">';
    while ($ind = $indQ->fetch_assoc()) {
        $col = ucwords(str_replace(' ', '_', $ind['name']));
        $value = (isset($row[$col]) && $row[$col] !== '')
            ? $row[$col]
            : '-';

        $hasil .= "<li>{$ind['name']} : {$value}</li>";
    }
    $hasil .= '</ul>';

    /* status */
    $statusBadge = $row['is_verified'] == 1
        ? '<span class="badge badge-success">Sudah diverifikasi</span>'
        : '<span class="badge badge-dark text-white">Belum diverifikasi</span>';

    $data[] = [
        'id' => $row['id'],
        'timestamp' => $row['created_at'],
        'material' => $row['material'],
        'user' => $row['user'],
        'hasil_analisa' => $hasil,
        'status' => $statusBadge,
        'action' => '
            <a href="analisa_edit.php?id='.$row['id'].'"
               class="btn btn-success btn-sm">Edit</a>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
