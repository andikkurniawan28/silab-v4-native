<?php
include('db.php');

$columns = ['id','judul','deskripsi','filename'];

$limit  = $_POST['length'];
$start  = $_POST['start'];
$order  = $columns[$_POST['order'][0]['column']] ?? 'id';
$dir    = $_POST['order'][0]['dir'] ?? 'desc';
$search = $_POST['search']['value'];

$where = '';
if ($search) {
    $where = "WHERE judul LIKE '%$search%'
              OR deskripsi LIKE '%$search%'
              OR filename LIKE '%$search%'";
}

$totalData = $conn->query("
    SELECT COUNT(*) FROM features
")->fetch_row()[0];

$totalFiltered = $conn->query("
    SELECT COUNT(*) FROM features $where
")->fetch_row()[0];

$sql = "
    SELECT * FROM features
    $where
    ORDER BY $order $dir
    LIMIT $start,$limit
";

$q = $conn->query($sql);
$data = [];

while($r = $q->fetch_assoc()){
    $data[] = [
        'id' => $r['id'],
        'judul' => htmlspecialchars($r['judul']),
        'deskripsi' => htmlspecialchars($r['deskripsi']),
        'filename' => htmlspecialchars($r['filename']),
        'action' => '
            <a href="feature_edit.php?id='.$r['id'].'"
               class="btn btn-warning btn-sm">Edit</a>
            <a href="feature_delete.php?id='.$r['id'].'"
                class="btn btn-danger btn-sm"
                onclick="return confirm(\'Hapus fitur ini?\')">
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
