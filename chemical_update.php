<?php
include('db.php');

$stmt = $conn->prepare("
    UPDATE chemicals SET name=? WHERE id=?
");
$stmt->bind_param("si", $_POST['name'], $_POST['id']);
$stmt->execute();

header("Location: chemical_index.php");
exit;
