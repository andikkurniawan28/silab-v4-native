<?php
include('session_manager.php');

if (empty($_POST['name'])) {
    die('Nama role wajib diisi');
}

$name = mysqli_real_escape_string($conn, $_POST['name']);

/**
 * Insert role
 */
$conn->query("
    INSERT INTO roles (name)
    VALUES ('$name')
");

$role_id = $conn->insert_id;

/**
 * Insert permissions
 */
if (!empty($_POST['features']) && is_array($_POST['features'])) {

    foreach ($_POST['features'] as $feature_id) {

        $feature_id = intval($feature_id);

        $conn->query("
            INSERT INTO permissions (role_id, feature_id)
            VALUES ($role_id, $feature_id)
        ");
    }
}

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header("Location: role_index.php");
exit;
