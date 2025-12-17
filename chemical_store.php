<?php
include('db.php');

$stmt = $conn->prepare("
    INSERT INTO chemicals (name)
    VALUES (?)
");
$stmt->bind_param("s", $_POST['name']);
$stmt->execute();

header("Location: chemical_index.php");
exit;
