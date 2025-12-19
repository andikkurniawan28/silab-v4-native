<?php
include('session_manager.php');

$judul     = mysqli_real_escape_string($conn, $_POST['judul']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$filename  = mysqli_real_escape_string($conn, $_POST['filename']);

$conn->query("
    INSERT INTO features (judul, deskripsi, filename)
    VALUES ('$judul', '$deskripsi', '$filename')
");

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header("Location: feature_index.php");
exit;
