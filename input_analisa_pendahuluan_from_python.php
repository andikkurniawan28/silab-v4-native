<?php
/**
 * API Core Sample
 * - GET data berdasarkan nomor_gelas
 * - UPDATE data → hanya 2 kemungkinan: sukses / gagal
 */

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
   Helper Response
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
   Validasi Awal
======================= */

if (empty($_GET['nomor_gelas'])) {
    response_error('Parameter nomor_gelas wajib diisi', 400);
}

$code = trim($_GET['nomor_gelas']);

/* =======================
   Ambil Data Utama
======================= */

$sql = "SELECT * FROM analisa_pendahuluans WHERE code = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    response_error();
}

mysqli_stmt_bind_param($stmt, "s", $code);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    response_error('Data tidak ditemukan', 404);
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* =======================
   MODE UPDATE
======================= */

if (
    isset($_GET['posisi'], $_GET['brix'], $_GET['pol'], $_GET['pol_baca'], $_GET['rendemen'])
) {

    $posisi = strtolower(trim($_GET['posisi']));
    $allowed_posisi = ['atas', 'tengah', 'bawah'];

    if (!in_array($posisi, $allowed_posisi)) {
        response_error('Posisi tidak valid', 400);
    }

    if (
        !is_numeric($_GET['brix']) ||
        !is_numeric($_GET['pol']) ||
        !is_numeric($_GET['pol_baca']) ||
        !is_numeric($_GET['rendemen'])
    ) {
        response_error('Nilai tidak valid', 400);
    }

    $columns = [
        'atas'   => ['brix_atas', 'pol_atas', 'pol_baca_atas', 'rendemen_atas'],
        'tengah' => ['brix_tengah', 'pol_tengah', 'pol_baca_tengah', 'rendemen_tengah'],
        'bawah'  => ['brix_bawah', 'pol_bawah', 'pol_baca_bawah', 'rendemen_bawah']
    ];

    [$brix_col, $pol_col, $pol_baca_col, $rendemen_col] = $columns[$posisi];

    $update_sql = "
        UPDATE analisa_pendahuluans
        SET
            {$brix_col} = ?,
            {$pol_col} = ?,
            {$pol_baca_col} = ?,
            {$rendemen_col} = ?,
            updated_at = NOW()
        WHERE id = ?
    ";

    $update_stmt = mysqli_prepare($conn, $update_sql);

    if (!$update_stmt) {
        response_error();
    }

    $brix_val = (float) $_GET['brix'];
    $pol_val = (float) $_GET['pol'];
    $pol_baca_val = (float) $_GET['pol_baca'];
    $rendemen_val = (float) $_GET['rendemen'];

    mysqli_stmt_bind_param(
        $update_stmt,
        "ddddi",
        $brix_val,
        $pol_val,
        $pol_baca_val,
        $rendemen_val,
        $data['id']
    );

    if (mysqli_stmt_execute($update_stmt)) {
        mysqli_stmt_close($update_stmt);
        response_success();
    } else {
        mysqli_stmt_close($update_stmt);
        response_error();
    }
}

/* =======================
   MODE TAMPIL DATA
======================= */

echo json_encode([
    'status' => 'success',
    'data' => [
        'id' => $data['id'],
        'code' => $data['code'],
        'kud_id' => $data['kud_id'],

        'atas' => [
            'brix' => $data['brix_atas'],
            'pol' => $data['pol_atas'],
            'pol_baca' => $data['pol_baca_atas'],
            'rendemen' => $data['rendemen_atas']
        ],
        'tengah' => [
            'brix' => $data['brix_tengah'],
            'pol' => $data['pol_tengah'],
            'pol_baca' => $data['pol_baca_tengah'],
            'rendemen' => $data['rendemen_tengah']
        ],
        'bawah' => [
            'brix' => $data['brix_bawah'],
            'pol' => $data['pol_bawah'],
            'pol_baca' => $data['pol_baca_bawah'],
            'rendemen' => $data['rendemen_bawah']
        ],

        'created_at' => $data['created_at'],
        'updated_at' => $data['updated_at']
    ]
]);

mysqli_close($conn);
