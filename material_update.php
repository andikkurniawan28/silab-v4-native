<?php
include('session_manager.php');

$conn->begin_transaction();

try {

    $stmt = $conn->prepare("
        UPDATE materials
        SET station_id=?, name=?
        WHERE id=?
    ");
    $stmt->bind_param("isi",
        $_POST['station_id'],
        $_POST['name'],
        $_POST['id']
    );
    $stmt->execute();

    $conn->query("
        DELETE FROM methods WHERE material_id=".$_POST['id']
    );

    if (!empty($_POST['indicator_ids'])) {
        $stmt2 = $conn->prepare("
            INSERT INTO methods (material_id, indicator_id)
            VALUES (?,?)
        ");
        foreach($_POST['indicator_ids'] as $ind){
            $stmt2->bind_param("ii", $_POST['id'], $ind);
            $stmt2->execute();
        }
    }

    $conn->commit();
    $_SESSION['flash'] = [
        'type' => 'success',
        'title' => 'Berhasil',
        'message' => 'Data berhasil diupdate'
    ];
    header("Location: material_index.php");

} catch(Exception $e){
    $conn->rollback();
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Gagal simpan',
        'message' => 'Data gagal diupdate'
    ];
}
