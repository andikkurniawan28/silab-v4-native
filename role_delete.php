<?php
include('db.php');

$stmt = $conn->prepare("DELETE FROM roles WHERE id=?");
$stmt->bind_param("i", $_POST['id']);
$stmt->execute();
