<?php
include('db.php');

$stmt = $conn->prepare("
    INSERT INTO kspots (name)
    VALUES (?)
");
$stmt->bind_param("s", $_POST['name']);
$stmt->execute();

header("Location: kspot_index.php");
exit;
