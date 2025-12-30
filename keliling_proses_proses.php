<?php
include('session_manager.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    // Ambil semua kspot
    $kspotQuery = $conn->query("SELECT id FROM kspots ORDER BY id");

    $columns   = [];
    $values    = [];
    $updateSet = [];

    while ($kspot = $kspotQuery->fetch_assoc()) {
        $colName = 'p' . $kspot['id'];

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
        $sql = "UPDATE keliling_proses SET $updateSql WHERE id = '$id'";

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

        $sql = "INSERT INTO keliling_proses ($columnsSql) VALUES ($valuesSql)";

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

    header("Location: keliling_proses_index.php");
    exit;
}
?>
