<?php
include('db.php');

$columns = ['id', 'code'];

$limit  = intval($_POST['length'] ?? 10);
$start  = intval($_POST['start'] ?? 0);
$order  = $columns[$_POST['order'][0]['column'] ?? 0];
$dir    = $_POST['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
$search = $conn->real_escape_string($_POST['search']['value'] ?? '');

/* BASE SUBQUERY — hanya 1000 data terakhir */
$baseTable = "
    (
        SELECT *
        FROM analisa_pendahuluans
        ORDER BY id DESC
        LIMIT 1000
    ) AS t
";

/* WHERE */
$where = "WHERE 1=1";
if ($search !== '') {
    $where .= " AND code LIKE '%$search%'";
}

/* total data (max 1000) */
$totalData = $conn->query("
    SELECT COUNT(*) FROM $baseTable
")->fetch_row()[0];

/* total filtered */
$totalFiltered = $conn->query("
    SELECT COUNT(*) FROM $baseTable $where
")->fetch_row()[0];

/* data */
$sql = "
    SELECT *
    FROM $baseTable
    $where
    ORDER BY $order $dir
    LIMIT $start, $limit
";

$query = $conn->query($sql);

$data = [];

while ($row = $query->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'code' => $row['code'],
        'wilayah' => $row['wilayah'],
        'brix_atas' => $row['brix_atas'] !== null ? number_format($row['brix_atas'], 2, ',', '.') : '-',
        'pol_atas' => $row['pol_atas'] !== null ? number_format($row['pol_atas'], 2, ',', '.') : '-',
        'rendemen_atas' => $row['rendemen_atas'] !== null ? number_format($row['rendemen_atas'], 2, ',', '.') : '-',
        'brix_tengah' => $row['brix_tengah'] !== null ? number_format($row['brix_tengah'], 2, ',', '.') : '-',
        'pol_tengah' => $row['pol_tengah'] !== null ? number_format($row['pol_tengah'], 2, ',', '.') : '-',
        'rendemen_tengah' => $row['rendemen_tengah'] !== null ? number_format($row['rendemen_tengah'], 2, ',', '.') : '-',
        'brix_bawah' => $row['brix_bawah'] !== null ? number_format($row['brix_bawah'], 2, ',', '.') : '-',
        'pol_bawah' => $row['pol_bawah'] !== null ? number_format($row['pol_bawah'], 2, ',', '.') : '-',
        'rendemen_bawah' => $row['rendemen_bawah'] !== null ? number_format($row['rendemen_bawah'], 2, ',', '.') : '-',
        'timestamp' => $row['created_at'],
        
        // Kolom berat_tebu (atas, tengah, bawah)
        'berat_tebu_atas' => isset($row['berat_tebu_atas']) && $row['berat_tebu_atas'] !== null ? number_format($row['berat_tebu_atas'], 2, ',', '.') : '-',
        'berat_tebu_tengah' => isset($row['berat_tebu_tengah']) && $row['berat_tebu_tengah'] !== null ? number_format($row['berat_tebu_tengah'], 2, ',', '.') : '-',
        'berat_tebu_bawah' => isset($row['berat_tebu_bawah']) && $row['berat_tebu_bawah'] !== null ? number_format($row['berat_tebu_bawah'], 2, ',', '.') : '-',
        
        // Kolom berat_nira (atas, tengah, bawah)
        'berat_nira_atas' => isset($row['berat_nira_atas']) && $row['berat_nira_atas'] !== null ? number_format($row['berat_nira_atas'], 2, ',', '.') : '-',
        'berat_nira_tengah' => isset($row['berat_nira_tengah']) && $row['berat_nira_tengah'] !== null ? number_format($row['berat_nira_tengah'], 2, ',', '.') : '-',
        'berat_nira_bawah' => isset($row['berat_nira_bawah']) && $row['berat_nira_bawah'] !== null ? number_format($row['berat_nira_bawah'], 2, ',', '.') : '-',
        
        // Kolom lama (untuk kompatibilitas jika masih ada)
        'berat_tebu' => isset($row['berat_tebu']) && $row['berat_tebu'] !== null ? number_format($row['berat_tebu'], 2, ',', '.') : '-',
        'berat_nira' => isset($row['berat_nira']) && $row['berat_nira'] !== null ? number_format($row['berat_nira'], 2, ',', '.') : '-',
        
        // Kolom pol_baca (jika ada)
        'pol_baca_atas' => isset($row['pol_baca_atas']) && $row['pol_baca_atas'] !== null ? number_format($row['pol_baca_atas'], 2, ',', '.') : '-',
        'pol_baca_tengah' => isset($row['pol_baca_tengah']) && $row['pol_baca_tengah'] !== null ? number_format($row['pol_baca_tengah'], 2, ',', '.') : '-',
        'pol_baca_bawah' => isset($row['pol_baca_bawah']) && $row['pol_baca_bawah'] !== null ? number_format($row['pol_baca_bawah'], 2, ',', '.') : '-',
        
        'action' => '
            <a href="analisa_pendahuluan_edit.php?id='.$row['id'].'" 
               class="btn btn-warning btn-sm">
                Edit
            </a>
        '
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);