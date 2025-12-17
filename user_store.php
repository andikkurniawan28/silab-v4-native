<?php
include('db.php');

$hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

$stmt = $conn->prepare("
    INSERT INTO users (name, username, role_id, password, is_active)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssisi",
    $_POST['name'],
    $_POST['username'],
    $_POST['role_id'],
    $hash,
    $_POST['is_active']
);

$stmt->execute();

header("Location: user_index.php");
exit;
