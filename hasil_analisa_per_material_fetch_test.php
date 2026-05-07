<?php
require_once 'db.php';

$material_id = intval($_GET['material_id'] ?? 0);

// ========================
// DATATABLES PARAM
// ========================
$draw        = intval($_POST['draw'] ?? 0);
$start       = intval($_POST['start'] ?? 0);
$length      = intval($_POST['length'] ?? 10);
$searchValue = trim($_POST['search']['value'] ?? '');

// ========================
// BASE UNION QUERY
// ========================
$baseUnion = "
    SELECT 
        id,
        material_id,
        is_verified,
        created_at
    FROM analisa_off_farm_new
    WHERE material_id = ?
    AND is_verified = 1

    UNION ALL

    SELECT 
        af.id,
        s.material_id,
        af.is_verified,
        s.created_at
    FROM analisa_off_farms af
    JOIN samples s ON s.id = af.sample_id
    WHERE s.material_id = ?
    AND af.is_verified = 1
";

// ========================
// SEARCH FILTER
// ========================
$searchSql = "";
$params = [$material_id, $material_id];
$types  = "ii";

if ($searchValue !== '') {
    $searchSql = " WHERE id LIKE ? ";
    $params[] = "%{$searchValue}%";
    $types   .= "s";
}

// ========================
// TOTAL ALL
// ========================
$totalSql = "
    SELECT COUNT(*) as total
    FROM ($baseUnion) as combined
";

$totalStmt = $conn->prepare($totalSql);
$totalStmt->bind_param("ii", $material_id, $material_id);
$totalStmt->execute();
$recordsTotal = $totalStmt->get_result()->fetch_assoc()['total'];

// ========================
// TOTAL FILTERED
// ========================
$filteredSql = "
    SELECT COUNT(*) as total
    FROM ($baseUnion) as combined
    $searchSql
";

$filteredStmt = $conn->prepare($filteredSql);
$filteredStmt->bind_param($types, ...$params);
$filteredStmt->execute();
$recordsFiltered = $filteredStmt->get_result()->fetch_assoc()['total'];

// ========================
// DATA QUERY
// ========================
$dataSql = "
    SELECT *
    FROM ($baseUnion) as combined
    $searchSql
    ORDER BY created_at DESC
    LIMIT ?, ?
";

$params[] = $start;
$params[] = $length;
$types   .= "ii";

$dataStmt = $conn->prepare($dataSql);
$dataStmt->bind_param($types, ...$params);
$dataStmt->execute();

$result = $dataStmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// ========================
// RESPONSE JSON
// ========================
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => (int)$recordsTotal,
    "recordsFiltered" => (int)$recordsFiltered,
    "data"            => $data
]);