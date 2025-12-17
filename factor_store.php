<?php
include('db.php');

$stmt = $conn->prepare("
    INSERT INTO factors (name, value)
    VALUES (?, ?)
");
$stmt->bind_param("sd", $_POST['name'], $_POST['value']);
$stmt->execute();

header("Location: factor_index.php");
exit;
