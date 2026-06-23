<?php
date_default_timezone_set('Asia/Jakarta');

// $host     = "192.168.28.60";
$host     = "192.168.20.234";
$username = "andik";
$password = "andik";
$database = "qc";

mysqli_report(MYSQLI_REPORT_OFF);

$conn2 = mysqli_init();
mysqli_options($conn2, MYSQLI_OPT_CONNECT_TIMEOUT, 1); // timeout 1 detik

$connected = @mysqli_real_connect(
    $conn2,
    $host,
    $username,
    $password,
    $database
);

if (!$connected) {
    $conn2 = null;
}
?>