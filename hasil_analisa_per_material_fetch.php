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
// WHERE BASE
// ========================
$whereSql = "WHERE material_id = ? AND is_verified = 1";
$params   = [$material_id];
$types    = "i";

// ========================
// SEARCH FILTER
// ========================
if ($searchValue !== '') {
    $whereSql .= "
        AND (
            id LIKE ?
        )
    ";
    
    // OR created_at LIKE ?
    // OR timestamp_riil LIKE ?

    $searchLike = "%{$searchValue}%";
    $params[] = $searchLike;
    // $params[] = $searchLike;
    // $params[] = $searchLike;
    // $types   .= "sss";
    $types   .= "s";
}

// ========================
// TOTAL FILTERED
// ========================
$countSql = "
    SELECT COUNT(*) AS total
    FROM analisa_off_farm_new
    $whereSql
";

$countStmt = $conn->prepare($countSql);
$countStmt->bind_param($types, ...$params);
$countStmt->execute();
$recordsFiltered = $countStmt->get_result()->fetch_assoc()['total'];

// ========================
// TOTAL ALL (tanpa search)
// ========================
$totalStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM analisa_off_farm_new
    WHERE material_id = ? AND 
    is_verified = 1
");
$totalStmt->bind_param("i", $material_id);
$totalStmt->execute();
$recordsTotal = $totalStmt->get_result()->fetch_assoc()['total'];

// ========================
// DATA QUERY
// ========================
$dataSql = "
    SELECT *
    FROM analisa_off_farm_new
    $whereSql
    ORDER BY id DESC
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
    "recordsTotal"    => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data"            => $data
]);
