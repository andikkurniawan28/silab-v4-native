<?php
include('db.php');

$stmt = $conn->prepare("INSERT INTO stations (name) VALUES (?)");
$stmt->bind_param("s", $_POST['name']);
$stmt->execute();

header("Location: station_index.php");
exit;
