<?php
include('session_manager.php');

$stmt = $conn->prepare("
    INSERT INTO factors (name, value)
    VALUES (?, ?)
");
$stmt->bind_param("sd", $_POST['name'], $_POST['value']);
$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header("Location: factor_index.php");
exit;
