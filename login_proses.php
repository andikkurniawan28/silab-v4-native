<?php
session_start();
include 'db.php';

// Ambil input
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi sederhana
if ($username == '' || $password == '') {
    header("Location: login.php?error=Username dan password wajib diisi");
    exit;
}

/**
 * 1. Ambil user aktif berdasarkan username
 */
$query = "
    SELECT id, username, password, name, role_id 
    FROM users
    WHERE username = '$username'
      AND is_active = 1
    LIMIT 1
";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {

    $user = mysqli_fetch_assoc($result);

    /**
     * 2. Cek password (setara Auth::attempt)
     */
    if (password_verify($password, $user['password'])) {

        // Regenerate session (setara $request->session()->regenerate())
        session_regenerate_id(true);

        // Simpan data user ke session
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['login']    = true;

        // Redirect seperti redirect()->intended()
        $redirect = $_SESSION['intended_url'] ?? 'index.php';
        unset($_SESSION['intended_url']);

        header("Location: $redirect");
        exit;

    } else {
        // Password salah
        header("Location: login.php?error=Username / password wrong.");
        exit;
    }

} else {
    // User tidak ditemukan / tidak aktif
    header("Location: login.php?error=Username / password wrong.");
    exit;
}
