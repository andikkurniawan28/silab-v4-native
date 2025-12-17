<?php
include('db.php');

$stmt = $conn->prepare("
    INSERT INTO indicators (name)
    VALUES (?)
");
$stmt->bind_param("s", $_POST['name']);
$stmt->execute();

header("Location: indicator_index.php");
exit;
