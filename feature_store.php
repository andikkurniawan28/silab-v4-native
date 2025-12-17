<?php
include('db.php');

$judul     = mysqli_real_escape_string($conn, $_POST['judul']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$filename  = mysqli_real_escape_string($conn, $_POST['filename']);

$conn->query("
    INSERT INTO features (judul, deskripsi, filename)
    VALUES ('$judul', '$deskripsi', '$filename')
");

header("Location: feature_index.php");
exit;
