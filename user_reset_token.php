<?php
include('session_manager.php');
checkRoleAccess([
    'Superadmin'
]);

include('db.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID tidak valid");
}

/**
 * =========================
 * RESET DEVICE TOKEN
 * =========================
 */
$stmt = $conn->prepare("
    UPDATE users
    SET device_token = NULL
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();

/**
 * =========================
 * REDIRECT
 * =========================
 */
header("Location: user_index.php?success=Token berhasil direset");
exit;