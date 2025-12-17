<?php
include('db.php');

$stmt = $conn->prepare("
    DELETE FROM factors WHERE id=?
");
$stmt->bind_param("i", $_POST['id']);
$stmt->execute();
