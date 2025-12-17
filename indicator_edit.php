<?php
include('header.php');

$id = $_GET['id'];

$indicator = $conn->query("
    SELECT * FROM indicators WHERE id=$id
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Indikator</h4>

    <form method="POST" action="indicator_update.php">
        <input type="hidden" name="id"
               value="<?= $indicator['id']; ?>">

        <div class="form-group">
            <label>Nama Indikator</label>
            <input type="text" name="name"
                   class="form-control"
                   value="<?= $indicator['name']; ?>"
                   required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="indicator_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
