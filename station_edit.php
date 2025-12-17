<?php
include('header.php');

$id = $_GET['id'];

$station = $conn->query("
    SELECT * FROM stations WHERE id = $id
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Stasiun</h4>

    <form method="POST" action="station_update.php">
        <input type="hidden" name="id" value="<?= $station['id']; ?>">

        <div class="form-group">
            <label>Nama Stasiun</label>
            <input type="text" name="name" class="form-control"
                   value="<?= $station['name']; ?>" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="station_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
