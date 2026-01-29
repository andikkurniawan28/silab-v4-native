<?php
/**
 * API Core Sample untuk Analisa On Farms
 * - GET data berdasarkan kartu_ari yang rendemen_ari NULL
 * - UPDATE data berdasarkan id → hanya 2 kemungkinan: sukses / gagal
 * - INSERT ke tabel kartu_aris setelah update berhasil
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
   MODE 1: Ambil Data yang Belum Diisi (rendemen_ari IS NULL)
======================= */

if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET['kartu_ari'])) {
    // Ambil semua data yang belum diisi rendemen_ari
    $sql = "SELECT id, kartu_ari FROM analisa_on_farms WHERE rendemen_ari IS NULL ORDER BY created_at ASC LIMIT 10";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        response_error('Gagal mengambil data', 500);
    }
    
    $data_list = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data_list[] = [
            'id' => $row['id'],
            'kartu_ari' => $row['kartu_ari']
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $data_list,
        'count' => count($data_list),
        'message' => 'Data kartu_ari yang belum diisi rendemen_ari'
    ]);
    exit();
}

/* =======================
   MODE 2: Validasi Awal untuk GET DATA
======================= */

if (empty($_GET['kartu_ari']) && empty($_GET['id'])) {
    response_error('Parameter kartu_ari atau id wajib diisi', 400);
}

// Prioritaskan pencarian berdasarkan id jika ada
if (!empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    $sql = "SELECT * FROM analisa_on_farms WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        response_error('Gagal mempersiapkan query', 500);
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
} else {
    // Fallback ke kartu_ari
    $kartu_ari = trim($_GET['kartu_ari']);
    $sql = "SELECT * FROM analisa_on_farms WHERE kartu_ari = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        response_error('Gagal mempersiapkan query', 500);
    }
    
    mysqli_stmt_bind_param($stmt, "s", $kartu_ari);
}

/* =======================
   Ambil Data Utama
======================= */

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    response_error('Data tidak ditemukan', 404);
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* =======================
   MODE UPDATE DATA ANALISA (UPDATE berdasarkan ID)
======================= */

if (
    isset($_GET['brix_ari'], $_GET['pol_ari'], $_GET['pol_baca_ari'], $_GET['rendemen_ari'])
) {
    // Validasi bahwa id ada dalam data yang ditemukan
    if (empty($data['id'])) {
        response_error('ID tidak ditemukan dalam data', 400);
    }
    
    $update_id = $data['id'];
    $kartu_ari_value = $data['kartu_ari'];
    
    // Validasi input numeric
    if (
        !is_numeric($_GET['brix_ari']) ||
        !is_numeric($_GET['pol_ari']) ||
        !is_numeric($_GET['pol_baca_ari']) ||
        !is_numeric($_GET['rendemen_ari'])
    ) {
        response_error('Nilai tidak valid. Harus berupa angka', 400);
    }
    
    // Konversi ke float
    $brix_ari = (float) $_GET['brix_ari'];
    $pol_ari = (float) $_GET['pol_ari'];
    $pol_baca_ari = (float) $_GET['pol_baca_ari'];
    $rendemen_ari = (float) $_GET['rendemen_ari'];
    
    // Mulai transaction
    mysqli_begin_transaction($conn);
    
    try {
        // 1. UPDATE tabel analisa_on_farms berdasarkan ID
        $update_sql = "
            UPDATE analisa_on_farms
            SET
                brix_ari = ?,
                pol_ari = ?,
                pol_baca_ari = ?,
                rendemen_ari = ?,
                updated_at = NOW()
            WHERE id = ?
        ";
        
        $update_stmt = mysqli_prepare($conn, $update_sql);
        
        if (!$update_stmt) {
            throw new Exception('Gagal mempersiapkan query update');
        }
        
        mysqli_stmt_bind_param(
            $update_stmt,
            "ddddi",
            $brix_ari,
            $pol_ari,
            $pol_baca_ari,
            $rendemen_ari,
            $update_id
        );
        
        if (!mysqli_stmt_execute($update_stmt)) {
            throw new Exception('Gagal update data analisa');
        }
        
        $affected_rows = mysqli_stmt_affected_rows($update_stmt);
        mysqli_stmt_close($update_stmt);
        
        if ($affected_rows === 0) {
            throw new Exception('Tidak ada data yang diupdate. ID mungkin tidak valid.');
        }
        
        // 2. INSERT ke tabel kartu_aris dengan kartu_ari sebagai rfid
        // Cek dulu apakah rfid sudah ada di tabel kartu_aris
        $check_sql = "SELECT id FROM kartu_aris WHERE rfid = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        
        if ($check_stmt) {
            mysqli_stmt_bind_param($check_stmt, "s", $kartu_ari_value);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            $rfid_exists = (mysqli_stmt_num_rows($check_stmt) > 0);
            mysqli_stmt_close($check_stmt);
        } else {
            $rfid_exists = false;
        }
        
        if (!$rfid_exists) {
            $insert_sql = "
                INSERT INTO kartu_aris (rfid, created_at, updated_at)
                VALUES (?, NOW(), NOW())
            ";
            
            $insert_stmt = mysqli_prepare($conn, $insert_sql);
            
            if (!$insert_stmt) {
                throw new Exception('Gagal mempersiapkan query insert');
            }
            
            mysqli_stmt_bind_param($insert_stmt, "s", $kartu_ari_value);
            
            if (!mysqli_stmt_execute($insert_stmt)) {
                throw new Exception('Gagal insert ke tabel kartu_aris');
            }
            
            mysqli_stmt_close($insert_stmt);
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        // Respon sukses
        $message = 'Data berhasil disimpan';
        $message .= $rfid_exists ? ' (rfid sudah ada di kartu_aris)' : ' dan kartu_ari berhasil dimasukkan ke tabel kartu_aris';
        
        response_success($message);
        
    } catch (Exception $e) {
        // Rollback transaction jika ada error
        mysqli_rollback($conn);
        response_error($e->getMessage(), 500);
    }
    
    // Keluar setelah update berhasil
    exit();
}

/* =======================
   MODE TAMPIL DATA SAJA
======================= */

echo json_encode([
    'status' => 'success',
    'data' => [
        'id' => $data['id'],
        'kartu_ari' => $data['kartu_ari'],
        'brix_ari' => $data['brix_ari'],
        'pol_ari' => $data['pol_ari'],
        'pol_baca_ari' => $data['pol_baca_ari'],
        'rendemen_ari' => $data['rendemen_ari'],
        'created_at' => $data['created_at'],
        'updated_at' => $data['updated_at']
    ]
]);

mysqli_close($conn);