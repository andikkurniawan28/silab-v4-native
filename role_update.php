<?php
include('db.php');

$stmt = $conn->prepare("UPDATE roles SET name=? WHERE id=?");
$stmt->bind_param("si", $_POST['name'], $_POST['id']);
$stmt->execute();

header("Location: role_index.php");
exit;
