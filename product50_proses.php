<?php
include('session_manager.php');

/* ---------- HELPER ---------- */
function generateTimestamp($date, $hour){
    $created_at = $date.' '.str_pad($hour,2,'0',STR_PAD_LEFT).":00:00";
    return date('Y-m-d H:i:s', strtotime($created_at.' +1 minute'));
}

/* ---------- INPUT ---------- */
$date      = $_POST['tanggal'];
$hour      = $_POST['jam'];
$c1_input  = floatval($_POST['cronus_1']);
$c2_input  = floatval($_POST['cronus_2']);
$c3_input  = floatval($_POST['cronus_3']);

$created_at = generateTimestamp($date, $hour);

/* ---------- HITUNG ---------- */
$last = $conn->query("
    SELECT cronus_1, cronus_2, cronus_3
    FROM production_50
    ORDER BY id DESC
    LIMIT 1
")->fetch_assoc();

if ($last && $hour != 7) {
    $cronus_1 = $c1_input - $last['cronus_1'];
    $cronus_2 = $c2_input - $last['cronus_2'];
    $cronus_3 = $c3_input - $last['cronus_3'];
} else {
    $cronus_1 = $c1_input;
    $cronus_2 = $c2_input;
    $cronus_3 = $c3_input;
}

/* ---------- TOTAL KUINTAL ---------- */
$value = ($cronus_1 + $cronus_2 + $cronus_3) * 0.5;

/* ---------- INSERT ---------- */
$conn->query("
    INSERT INTO production_50
    (cronus_1, cronus_2, cronus_3, value, created_at)
    VALUES
    ('$cronus_1','$cronus_2','$cronus_3','$value','$created_at')
");

/* ---------- FLASH MESSAGE ---------- */
$_SESSION['flash'] = [
    'type'    => 'success',
    'title'   => 'Berhasil',
    'message' => 'Data Product 50 berhasil disimpan'
];

/* ---------- REDIRECT ---------- */
header('Location: product50_index.php');
exit;
