<?php
include('db.php');

$materials = "82,83,84,85,86,87,88,89,236,237";

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
        a.pH,
        a.TDS,
        a.Sadah,
        a.P2O5,
        a.Silika,
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
        'pH'        => $r['pH'] ?? '-',
        'TDS'       => $r['TDS'] ?? '-',
        'Sadah'     => $r['Sadah'] ?? '-',
        'P2O5'      => $r['P2O5'] ?? '-',
        'Silika'    => $r['Silika'] ?? '-',
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
