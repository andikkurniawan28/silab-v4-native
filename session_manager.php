<?php
/**
 * SESSION & FLASH MESSAGE MANAGER
 * Include di header.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'db.php';

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
 * 2. AMBIL ROLE NAME DARI DATABASE
 * =========================
 */
// if (!isset($_SESSION['role_name'])) {
//     $stmt = $conn->prepare("SELECT name FROM roles WHERE id = ?");
//     $stmt->bind_param("i", $role_id);
//     $stmt->execute();
//     $result = $stmt->get_result();
    
//     if ($result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $_SESSION['role_name'] = $row['name'];
//     } else {
//         $_SESSION['role_name'] = 'Unknown';
//     }
//     $stmt->close();
// }

$role_name = $_SESSION['role_name'];

/**
 * =========================
 * 3. FUNCTION CHECK ROLE ACCESS
 * =========================
 */
// Di fungsi checkRoleAccess(), tambahkan debugging:
function checkRoleAccess($allowed_roles = []) 
{
    global $role_name;
    
    // Debug 1: Tampilkan role yang sedang diproses
    error_log("=== ROLE CHECK START ===");
    error_log("User Role: " . $role_name);
    error_log("Allowed Roles: " . print_r($allowed_roles, true));
    
    // Bersihkan array dari elemen kosong dan whitespace
    $cleaned_allowed_roles = array_map('trim', $allowed_roles);
    $cleaned_allowed_roles = array_filter($cleaned_allowed_roles, function($value) {
        return $value !== '';
    });
    
    error_log("Cleaned Allowed Roles: " . print_r($cleaned_allowed_roles, true));
    
    // Jika array kosong setelah dibersihkan, berikan akses
    if (empty($cleaned_allowed_roles)) {
        error_log("No roles specified, access granted");
        return true;
    }
    
    // Trim role name pengguna juga
    $user_role = trim($role_name);
    error_log("User Role (trimmed): " . $user_role);
    
    // Cek case-insensitive
    $user_role_lower = strtolower($user_role);
    $allowed_roles_lower = array_map('strtolower', $cleaned_allowed_roles);
    
    error_log("User Role (lower): " . $user_role_lower);
    error_log("Allowed Roles (lower): " . print_r($allowed_roles_lower, true));
    
    if (!in_array($user_role_lower, $allowed_roles_lower)) {
        error_log("ACCESS DENIED for role: " . $user_role);
        error_log("=== ROLE CHECK END (DENIED) ===");
        
        // Tampilkan SweetAlert dan STOP eksekusi
        echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses ditolak',
                        text: 'Anda tidak dapat mengakses halaman ini!!!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.history.back();
                        }
                    });
                });
            </script>
            ";
        exit; // INI YANG PENTING! Hentikan eksekusi PHP
    }
    
    error_log("ACCESS GRANTED for role: " . $user_role);
    error_log("=== ROLE CHECK END (GRANTED) ===");
    return true;
}

/**
 * =========================
 * 4. FUNCTION GET ROLE NAME
 * =========================
 */
function getRoleName() {
    global $role_name;
    return $role_name;
}

/**
 * =========================
 * 5. FUNCTION GET USER ROLE ID
 * =========================
 */
function getUserRoleId() {
    global $role_id;
    return $role_id;
}

/**
 * =========================
 * 6. FLASH MESSAGE
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