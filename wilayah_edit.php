<?php
include('header.php');

$id = $_GET['id'];

$wilayah = $conn->query("
    SELECT * FROM kuds WHERE id = $id
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Wilayah</h4>

    <form method="POST" action="wilayah_update.php">
        <input type="hidden" name="id" value="<?= $wilayah['id']; ?>">

        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="code" class="form-control" value="<?= $wilayah['code']; ?>" autofocus required>
        </div>

        <div class="form-group">
            <label>Nama Wilayah</label>
            <input type="text" name="name" class="form-control"
                   value="<?= $wilayah['name']; ?>" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="wilayah_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
