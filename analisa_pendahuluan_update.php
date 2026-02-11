<?php
include('session_manager.php');

$id = intval($_POST['id']);

// Fungsi untuk konversi nilai ke NULL jika kosong
function nullIfEmpty($value) {
    return ($value === '' || $value === null) ? null : floatval($value);
}

// Konversi nilai dengan penanganan NULL
// Berat Tebu (Atas, Tengah, Bawah)
$berat_tebu_atas        = nullIfEmpty($_POST['berat_tebu_atas']);
$berat_tebu_tengah      = nullIfEmpty($_POST['berat_tebu_tengah']);
$berat_tebu_bawah       = nullIfEmpty($_POST['berat_tebu_bawah']);

// Berat Nira (Atas, Tengah, Bawah)
$berat_nira_atas        = nullIfEmpty($_POST['berat_nira_atas']);
$berat_nira_tengah      = nullIfEmpty($_POST['berat_nira_tengah']);
$berat_nira_bawah       = nullIfEmpty($_POST['berat_nira_bawah']);

// Analisa Atas
$brix_atas              = nullIfEmpty($_POST['brix_atas']);
$pol_atas               = nullIfEmpty($_POST['pol_atas']);
$pol_baca_atas          = nullIfEmpty($_POST['pol_baca_atas']);
$rendemen_atas          = nullIfEmpty($_POST['rendemen_atas']);

// Analisa Tengah
$brix_tengah            = nullIfEmpty($_POST['brix_tengah']);
$pol_tengah             = nullIfEmpty($_POST['pol_tengah']);
$pol_baca_tengah        = nullIfEmpty($_POST['pol_baca_tengah']);
$rendemen_tengah        = nullIfEmpty($_POST['rendemen_tengah']);

// Analisa Bawah
$brix_bawah             = nullIfEmpty($_POST['brix_bawah']);
$pol_bawah              = nullIfEmpty($_POST['pol_bawah']);
$pol_baca_bawah         = nullIfEmpty($_POST['pol_baca_bawah']);
$rendemen_bawah         = nullIfEmpty($_POST['rendemen_bawah']);

$stmt = $conn->prepare("
    UPDATE analisa_pendahuluans 
    SET 
        /* Berat Tebu (Atas, Tengah, Bawah) */
        berat_tebu_atas = ?,
        berat_tebu_tengah = ?,
        berat_tebu_bawah = ?,
        
        /* Berat Nira (Atas, Tengah, Bawah) */
        berat_nira_atas = ?,
        berat_nira_tengah = ?,
        berat_nira_bawah = ?,
        
        /* Analisa Atas */
        brix_atas = ?, 
        pol_atas = ?, 
        pol_baca_atas = ?, 
        rendemen_atas = ?,
        
        /* Analisa Tengah */
        brix_tengah = ?, 
        pol_tengah = ?, 
        pol_baca_tengah = ?, 
        rendemen_tengah = ?,
        
        /* Analisa Bawah */
        brix_bawah = ?, 
        pol_bawah = ?, 
        pol_baca_bawah = ?, 
        rendemen_bawah = ?
    WHERE id = ?
");

// Menggunakan bind_param dengan penanganan dinamis untuk NULL
$types = "";
$params = [];

// Urutan parameter sesuai dengan query
$fields = [
    // Berat Tebu (3)
    &$berat_tebu_atas, &$berat_tebu_tengah, &$berat_tebu_bawah,
    // Berat Nira (3)
    &$berat_nira_atas, &$berat_nira_tengah, &$berat_nira_bawah,
    // Analisa Atas (4)
    &$brix_atas, &$pol_atas, &$pol_baca_atas, &$rendemen_atas,
    // Analisa Tengah (4)
    &$brix_tengah, &$pol_tengah, &$pol_baca_tengah, &$rendemen_tengah,
    // Analisa Bawah (4)
    &$brix_bawah, &$pol_bawah, &$pol_baca_bawah, &$rendemen_bawah
];

// Tentukan tipe untuk setiap parameter
foreach ($fields as &$field) {
    if ($field === null) {
        $types .= "s"; // string untuk NULL
        $field = null;
    } else {
        $types .= "d"; // double untuk nilai numerik
    }
    $params[] = &$field;
}

// Tambah tipe untuk id
$types .= "i";
$params[] = &$id;

// Bind parameter menggunakan call_user_func_array
array_unshift($params, $types);
call_user_func_array([$stmt, 'bind_param'], $params);

try {
    $stmt->execute();
    
    $_SESSION['flash'] = [
        'type' => 'success',
        'title' => 'Berhasil',
        'message' => 'Data berhasil diupdate'
    ];
    
} catch (Exception $e) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Gagal',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ];
}

header("Location: analisa_pendahuluan_index.php");
exit;