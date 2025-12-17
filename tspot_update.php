<?php
include('db.php');

$stmt = $conn->prepare("
    UPDATE tspots SET name=? WHERE id=?
");
$stmt->bind_param("si", $_POST['name'], $_POST['id']);
$stmt->execute();

header("Location: tspot_index.php");
exit;
