<?php
include('db.php');

$stmt = $conn->prepare("UPDATE stations SET name=? WHERE id=?");
$stmt->bind_param("si", $_POST['name'], $_POST['id']);
$stmt->execute();

header("Location: station_index.php");
exit;
