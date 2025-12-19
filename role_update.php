<?php
include('session_manager.php');

if (empty($_POST['id']) || empty($_POST['name'])) {
    die('Data tidak valid');
}

$id   = intval($_POST['id']);
$name = mysqli_real_escape_string($conn, $_POST['name']);

/**
 * Update nama role
 */
$conn->query("
    UPDATE roles
    SET name = '$name'
    WHERE id = $id
");

/**
 * Reset permissions lama
 */
$conn->query("
    DELETE FROM permissions
    WHERE role_id = $id
");

/**
 * Insert permissions baru
 */
if (!empty($_POST['features']) && is_array($_POST['features'])) {

    foreach ($_POST['features'] as $feature_id) {

        $feature_id = intval($feature_id);

        $conn->query("
            INSERT INTO permissions (role_id, feature_id)
            VALUES ($id, $feature_id)
        ");
    }
}

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil diupdate'
];
header("Location: role_index.php");
exit;
