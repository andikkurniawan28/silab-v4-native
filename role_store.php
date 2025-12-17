<?php
include('db.php');

$stmt = $conn->prepare("INSERT INTO roles (name) VALUES (?)");
$stmt->bind_param("s", $_POST['name']);
$stmt->execute();

header("Location: role_index.php");
exit;
