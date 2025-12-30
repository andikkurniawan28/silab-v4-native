<?php
include 'db.php';

/*
 | Ambil semua ID dari chemicals
 */
$q = $conn->query("SELECT id FROM chemicals");

if (!$q) {
    die("Query chemicals gagal: " . $conn->error);
}

$alter = [];

while ($row = $q->fetch_assoc()) {
    $col = 'p' . $row['id'];

    // Cek apakah kolom sudah ada
    $cek = $conn->query("
        SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = 'penggunaan_bpp'
        AND COLUMN_NAME = '$col'
    ");

    if ($cek && $cek->num_rows == 0) {
        $alter[] = "ADD COLUMN `$col` FLOAT NULL";
    }
}

/*
 | Jika ada kolom baru → ALTER TABLE
 */
if (!empty($alter)) {

    $sql = "ALTER TABLE penggunaan_bpp " . implode(', ', $alter);

    if ($conn->query($sql)) {
        echo "Berhasil menambahkan kolom:<br>";
        echo implode('<br>', $alter);
    } else {
        echo "ALTER gagal: " . $conn->error;
    }

} else {
    echo "Tidak ada kolom baru yang perlu ditambahkan.";
}
