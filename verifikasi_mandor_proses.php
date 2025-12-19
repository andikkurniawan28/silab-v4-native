<?php
include('session_manager.php');

if (!isset($_POST['ids']) || count($_POST['ids']) == 0) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Tidak ada data yang dipilih'
    ];
    header('Location: verifikasi_mandor_index.php');
    exit;
}

$ids = array_map('intval', $_POST['ids']);
$idList = implode(',', $ids);

$verificator = $_SESSION['user_id'];
$conn->query("
    UPDATE analisa_off_farm_new
    SET is_verified = 1,
    user_id = '$verificator' 
    WHERE id IN ($idList)
");

$_SESSION['flash'] = [
    'title' => 'Berhasil',
    'type' => 'success',
    'message' => 'Data berhasil diverifikasi'
];

header('Location: verifikasi_mandor_index.php');
exit;
