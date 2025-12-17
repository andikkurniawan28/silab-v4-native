<?php
include('db.php');

$stmt = $conn->prepare("
    DELETE FROM tspots WHERE id=?
");
$stmt->bind_param("i", $_POST['id']);
$stmt->execute();
