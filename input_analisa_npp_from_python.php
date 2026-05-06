<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db.php';

/* =======================
   HELPER
======================= */

function response_success($message = 'Sukses simpan') {
    echo json_encode([
        'status' => 'success',
        'message' => $message
    ]);
    exit();
}

function response_error($message = 'Gagal simpan', $code = 500) {
    http_response_code($code);
    echo json_encode([
        'status' => 'error',
        'message' => $message
    ]);
    exit();
}

/* =======================
   VALIDASI PARAMETER
======================= */

$required = ['jam', 'menit', 'brix', 'pol', 'pol_baca', 'rendemen', 'purity'];

foreach ($required as $param) {
    if (!isset($_GET[$param]) || $_GET[$param] === '') {
        response_error("Parameter {$param} wajib diisi", 400);
    }
}

$jam = $_GET['jam'];
$menit = $_GET['menit'];

$brix = $_GET['brix'];
$pol = $_GET['pol'];
$pol_baca = $_GET['pol_baca'];
$rendemen = $_GET['rendemen'];
$purity = $_GET['purity'];

/* =======================
   VALIDASI NUMERIC
======================= */

if (
    !is_numeric($brix) ||
    !is_numeric($pol) ||
    !is_numeric($pol_baca) ||
    !is_numeric($rendemen) ||
    !is_numeric($purity)
) {
    response_error("Parameter harus numeric", 400);
}

/* =======================
   GENERATE TIMESTAMP
======================= */

try {
    $today = date('Y-m-d');
    $created_at = $today . " {$jam}:{$menit}:00";
} catch (Exception $e) {
    response_error("Format waktu tidak valid", 400);
}

/* =======================
   INSERT DATA
======================= */

$sql = "
    INSERT INTO analisa_off_farm_new
    (
        material_id,
        user_id,
        `%Brix`,
        `%Pol`,
        `Pol`,
        `HK`,
        `%R`,
        created_at,
        updated_at
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    response_error("Prepare gagal: " . mysqli_error($conn));
}

$material_id = 3;
$user_id = 1;

$brix_val = (float)$brix;
$pol_val = (float)$pol;
$pol_baca_val = (float)$pol_baca;
$purity_val = (float)$purity;
$rendemen_val = (float)$rendemen;

mysqli_stmt_bind_param(
    $stmt,
    "iiddddds",
    $material_id,
    $user_id,
    $brix_val,
    $pol_val,
    $pol_baca_val,
    $purity_val,
    $rendemen_val,
    $created_at
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    response_success("Data berhasil disimpan (Jam {$jam}:{$menit})");
} else {
    $err = mysqli_error($conn);
    mysqli_stmt_close($stmt);
    response_error("Gagal insert: " . $err);
}

mysqli_close($conn);