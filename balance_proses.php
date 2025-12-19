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
$tebu       = floatval($_POST['tebu']);
$totalizer  = floatval($_POST['totalizer']);

$created_at = generateTimestamp($date,$hour);

/* ---------- HITUNG ---------- */
$last = $conn->query("SELECT * FROM balances ORDER BY id DESC LIMIT 1")->fetch_assoc();

if($last){
    $factor = findFactor($conn,'Raw Juice');
    $flow_nm = $factor * ($totalizer - $last['totalizer']);
    $nm_persen_tebu = ($flow_nm / $tebu) * 1000;
}else{
    $flow_nm = 0;
    $nm_persen_tebu = 0;
}

$conn->query("
    INSERT INTO balances
    (tebu,totalizer,flow_nm,nm_persen_tebu,created_at,user_id)
    VALUES
    ('$tebu','$totalizer','$flow_nm','$nm_persen_tebu','$created_at','".$_SESSION['user_id']."')
");

$_SESSION['flash'] = [
    'type'=>'success',
    'title'=>'Berhasil',
    'message'=>'Data balance berhasil disimpan'
];

header('Location: balance_index.php');
exit;
