<?php
include('session_manager.php');

/* ---------- HELPER ---------- */
function generateTimestamp($date, $hour){
    $created_at = $date.' '.str_pad($hour,2,'0',STR_PAD_LEFT).":00:00";
    return date('Y-m-d H:i:s', strtotime($created_at.' +1 minute'));
}

function findFactor($conn, $name){
    return $conn->query("
        SELECT value FROM factors
        WHERE name='$name'
        ORDER BY id DESC LIMIT 1
    ")->fetch_assoc()['value'];
}

/* ---------- INPUT ---------- */
$date       = $_POST['date'];
$hour       = $_POST['created_at'];
$totalizer  = floatval($_POST['totalizer']);

$created_at = generateTimestamp($date,$hour);

/* ---------- HITUNG ---------- */
$last = $conn->query("SELECT * FROM imbibitions ORDER BY id DESC LIMIT 1")->fetch_assoc();

if($last){
    $flow_nm = 1 * ($totalizer - $last['totalizer']);
}else{
    $flow_nm = 0;
}

$conn->query("
    INSERT INTO imbibitions
    (totalizer,flow_imb,created_at,user_id)
    VALUES
    ('$totalizer','$flow_nm','$created_at','".$_SESSION['user_id']."')
");

$_SESSION['flash'] = [
    'type'=>'success',
    'title'=>'Berhasil',
    'message'=>'Data imbibition berhasil disimpan'
];

header('Location: imbibition_index.php');
exit;
