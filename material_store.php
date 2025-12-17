<?php
include('db.php');

$conn->begin_transaction();

try {

    $stmt = $conn->prepare("
        INSERT INTO materials (station_id, name)
        VALUES (?,?)
    ");
    $stmt->bind_param("is",
        $_POST['station_id'],
        $_POST['name']
    );
    $stmt->execute();

    $material_id = $conn->insert_id;

    if (!empty($_POST['indicator_ids'])) {
        $stmt2 = $conn->prepare("
            INSERT INTO methods (material_id, indicator_id)
            VALUES (?,?)
        ");
        foreach($_POST['indicator_ids'] as $ind){
            $stmt2->bind_param("ii", $material_id, $ind);
            $stmt2->execute();
        }
    }

    $conn->commit();
    header("Location: material_index.php");

} catch(Exception $e){
    $conn->rollback();
    echo "Error";
}
