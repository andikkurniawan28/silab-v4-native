<?php
/**
 * SESSION & FLASH MESSAGE MANAGER
 * Include di header.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'db_packer.php';

/**
 * =========================
 * 1. CEK LOGIN
 * =========================
 */
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    echo "<script>
        alert('Session berakhir, silakan login ulang');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

/**
 * =========================
 * 2. (DEV MODE) PERMISSION
 * =========================
 * SENGAJA DI NONAKTIFKAN
 */

// $currentFile = basename($_SERVER['PHP_SELF']);

// $q = $conn->query("
//     SELECT 1
//     FROM permissions p
//     JOIN features f ON f.id = p.feature_id
//     WHERE p.role_id = '$role_id'
//       AND f.filename = '$currentFile'
//     LIMIT 1
// ");

// if ($q->num_rows === 0) {
//     echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
//           <script>
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Akses Ditolak',
//                 text: 'Anda tidak memiliki akses ke halaman ini!',
//                 timer: 1500,
//                 showConfirmButton: false
//             }).then(() => {
//                 window.history.back();
//             });
//           </script>";
//     exit;
// }

/**
 * =========================
 * 3. FLASH MESSAGE
 * =========================
 */
if (isset($_SESSION['flash'])) {

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']); // HAPUS SETELAH DITAMPILKAN

    $icon  = $flash['type'] ?? 'success'; // success | error | warning | info
    $title = $flash['title'] ?? '';
    $text  = $flash['message'] ?? '';

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: '{$icon}',
                title: '{$title}',
                text: '{$text}',
                timer: 1500,
                showConfirmButton: false
            });
        });
    </script>
    ";
}
