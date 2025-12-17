<?php
include('db.php');

$stmt = $conn->prepare("
    UPDATE factors SET name=?, value=? WHERE id=?
");
$stmt->bind_param("sdi", $_POST['name'], $_POST['value'], $_POST['id']);
$stmt->execute();

header("Location: factor_index.php");
exit;
