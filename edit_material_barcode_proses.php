<?php
session_start();
include('db.php');

if (!isset($_POST['id'], $_POST['material_id'])) {
    die('Data tidak lengkap');
}

$id          = intval($_POST['id']);
$material_id = intval($_POST['material_id']);

$sql = "
    UPDATE analisa_off_farm_new
    SET material_id='$material_id'
    WHERE id='$id'
";

if (!$conn->query($sql)) {
    die('Gagal update material: ' . $conn->error);
}

header("Location: barcode_index.php?success=1");
exit;
