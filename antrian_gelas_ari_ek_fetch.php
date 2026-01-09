<?php
include('db.php');

$limit  = intval($_POST['length']);
$start  = intval($_POST['start']);

/**
 * TOTAL DATA
 */
$totalData = $conn->query("
    SELECT COUNT(*)
    FROM kartu_aris
    WHERE jenis = 'K'
")->fetch_row()[0];

/**
 * DATA
 */
$sql = "
    SELECT *
    FROM kartu_aris 
    WHERE jenis = 'K' 
    ORDER BY id DESC
    LIMIT $start,$limit
";

$q = $conn->query($sql);
$data = [];

while ($r = $q->fetch_assoc()) {
    $data[] = [
        'id'        => $r['id'],
        'rfid'      => $r['rfid'],
        'action'    => '
            <a href="antrian_gelas_ari_ek_delete.php?id='.$r['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data?\')">
               Hapus
            </a>
        '
    ];
}

echo json_encode([
    "draw"            => intval($_POST['draw']),
    "recordsTotal"    => $totalData,
    "recordsFiltered" => $totalData,
    "data"            => $data
]);
