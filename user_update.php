<?php
include('session_manager.php');

if (!empty($_POST['password'])) {

    // password diganti
    $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("
        UPDATE users 
        SET name=?, username=?, role_id=?, password=?, is_active=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssisii",
        $_POST['name'],
        $_POST['username'],
        $_POST['role_id'],
        $hash,
        $_POST['is_active'],
        $_POST['id']
    );

} else {

    // password tidak diubah
    $stmt = $conn->prepare("
        UPDATE users 
        SET name=?, username=?, role_id=?, is_active=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssiii",
        $_POST['name'],
        $_POST['username'],
        $_POST['role_id'],
        $_POST['is_active'],
        $_POST['id']
    );
}

$stmt->execute();

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];
header("Location: user_index.php");
exit;
