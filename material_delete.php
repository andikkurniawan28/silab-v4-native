<?php
include('db.php');

$conn->begin_transaction();

$conn->query("
    DELETE FROM methods WHERE material_id=".$_POST['id']
);

$stmt = $conn->prepare("
    DELETE FROM materials WHERE id=?
");
$stmt->bind_param("i", $_POST['id']);
$stmt->execute();

$conn->commit();
