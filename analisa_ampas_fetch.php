<?php
include('db.php');

$materials = "8,9,10,11,12,18,19,20,21,22,23,330,332,338,425";

$limit  = intval($_POST['length']);
$start  = intval($_POST['start']);

/**
 * TOTAL DATA
 */
$totalData = $conn->query("
    SELECT COUNT(*)
    FROM analisa_off_farm_new
    WHERE material_id IN ($materials)
")->fetch_row()[0];

/**
 * DATA
 */
$sql = "
    SELECT 
        a.id,
        a.created_at,
        a.material_id,
        a.Pol_Ampas,
        a.`%Air`,
        a.`%Zk`,
        m.name AS material
    FROM analisa_off_farm_new a
    JOIN materials m ON m.id = a.material_id
    WHERE a.material_id IN ($materials)
    ORDER BY a.id DESC
    LIMIT $start,$limit
";

$q = $conn->query($sql);
$data = [];

while ($r = $q->fetch_assoc()) {
    $data[] = [
        'id'        => $r['id'],
        'material'  => $r['material'],
        'sample_id' => $r['id'], // ID ANALISA = SAMPLE
        'pol_ampas' => $r['Pol_Ampas'] ?? '-',
        'air'       => $r['%Air'] ?? '-',
        'zk'        => $r['%Zk'] ?? '-',
        'created_at'=> $r['created_at'] ?? '-',
        'action'    => '
            <a href="analisa_ampas_delete.php?id='.$r['id'].'"
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
