<?php
include('session_manager.php');

$stmt = $conn->prepare("
    INSERT INTO tspots (name)
    VALUES (?)
");
$stmt->bind_param("s", $_POST['name']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header("Location: tspot_index.php");
exit;
