<?php
include('db.php');

$materials = "82,83,84,85,86,87,88,89,236,237";

/**
 * ===============================
 * DATATABLE (LIMIT 1000)
 * ===============================
 */
$limit = intval($_POST['length'] ?? 10);
$start = intval($_POST['start'] ?? 0);
$draw  = intval($_POST['draw'] ?? 1);

/**
 * BASE TABLE — 1000 DATA TERAKHIR
 */
$baseTable = "
    (
        SELECT *
        FROM analisa_off_farm_new
        WHERE material_id IN ($materials)
        ORDER BY id DESC
        LIMIT 1000
    ) AS a
";

/**
 * TOTAL DATA (maks 1000)
 */
$totalData = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
")->fetch_row()[0];

/**
 * DATA
 */
$q = $conn->query("
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
    FROM $baseTable
    JOIN materials m ON m.id = a.material_id
    ORDER BY a.id DESC
    LIMIT $start, $limit
");

$data = [];
while ($r = $q->fetch_assoc()) {
    $data[] = [
        'id'         => $r['id'],
        'material'   => $r['material'],
        'sample_id'  => $r['id'],
        'pH'         => $r['pH'] ?? '-',
        'TDS'        => $r['TDS'] ?? '-',
        'Sadah'      => $r['Sadah'] ?? '-',
        'P2O5'       => $r['P2O5'] ?? '-',
        'Silika'     => $r['Silika'] ?? '-',
        'created_at' => $r['created_at'] ?? '-',
        'action'     => '
            <a href="analisa_ampas_delete.php?id='.$r['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data?\')">
               Hapus
            </a>
        '
    ];
}

/**
 * RESPONSE
 */
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalData,
    "recordsFiltered" => $totalData,
    "data"            => $data
]);
