<?php
include('header.php');

$id = intval($_GET['id']);

$data = $conn->query("
    SELECT created_at, timestamp_riil
    FROM analisa_off_farm_new
    WHERE id='$id'
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Timestamp Barcode #<?= $id; ?></h4>

    <form method="POST" action="edit_timestamp_barcode_proses.php">
        <input type="hidden" name="id" value="<?= $id; ?>">

        <div class="form-group">
            <label>Created At</label>
            <input type="datetime-local"
                   name="created_at"
                   class="form-control"
                   value="<?= date('Y-m-d\TH:i', strtotime($data['created_at'])); ?>"
                   required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="barcode_index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('footer.php'); ?>
