<?php
include('db.php');

$id        = intval($_POST['id']);
$judul     = mysqli_real_escape_string($conn, $_POST['judul']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$filename  = mysqli_real_escape_string($conn, $_POST['filename']);

$conn->query("
    UPDATE features
    SET judul='$judul',
        deskripsi='$deskripsi',
        filename='$filename'
    WHERE id='$id'
");

header("Location: feature_index.php");
exit;
