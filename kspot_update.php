<?php
include('db.php');

$stmt = $conn->prepare("
    UPDATE kspots SET name=? WHERE id=?
");
$stmt->bind_param("si", $_POST['name'], $_POST['id']);
$stmt->execute();

header("Location: kspot_index.php");
exit;
