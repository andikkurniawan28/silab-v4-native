<?php
include('session_manager.php');

$stmt = $conn->prepare("
    INSERT INTO kspots (name)
    VALUES (?)
");
$stmt->bind_param("s", $_POST['name']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header("Location: kspot_index.php");
exit;
