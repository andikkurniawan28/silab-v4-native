<?php
include('header.php');

$id = $_GET['id'];

$factor = $conn->query("
    SELECT * FROM factors WHERE id=$id
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Faktor</h4>

    <form method="POST" action="factor_update.php">
        <input type="hidden" name="id"
               value="<?= $factor['id']; ?>">

        <div class="form-group">
            <label>Nama Faktor</label>
            <input type="text" name="name"
                   class="form-control"
                   value="<?= $factor['name']; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Value</label>
            <input type="number" step="0.01"
                   name="value"
                   class="form-control"
                   value="<?= $factor['value']; ?>"
                   required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="factor_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
