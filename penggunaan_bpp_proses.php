<?php
include('session_manager.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    // Ambil semua chemical
    $chemicalQuery = $conn->query("SELECT id FROM chemicals ORDER BY id");

    $columns   = [];
    $values    = [];
    $updateSet = [];

    while ($chemical = $chemicalQuery->fetch_assoc()) {
        $colName = 'p' . $chemical['id'];

        // Ambil nilai POST
        $rawValue = $_POST[$colName] ?? null;

        $columns[] = "`$colName`";

        if ($rawValue === null || $rawValue === '') {
            // BENAR-BENAR NULL
            $values[]    = "NULL";
            $updateSet[] = "`$colName` = NULL";
        } else {
            $safeValue   = $conn->real_escape_string($rawValue);
            $values[]    = "'$safeValue'";
            $updateSet[] = "`$colName` = '$safeValue'";
        }
    }

    if ($id) {
        // UPDATE
        $updateSql = implode(', ', $updateSet);
        $sql = "UPDATE penggunaan_bpp SET $updateSql WHERE id = '$id'";

        if ($conn->query($sql)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Data berhasil diperbarui'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Gagal memperbarui data: ' . $conn->error
            ];
        }
    } else {
        // INSERT
        $columnsSql = implode(', ', $columns);
        $valuesSql  = implode(', ', $values);

        $sql = "INSERT INTO penggunaan_bpp ($columnsSql) VALUES ($valuesSql)";

        if ($conn->query($sql)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Data berhasil ditambahkan'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Gagal menambahkan data: ' . $conn->error
            ];
        }
    }

    header("Location: penggunaan_bpp_index.php");
    exit;
}
?>
