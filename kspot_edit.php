<?php
include('header.php');

$id = $_GET['id'];

$kspot = $conn->query("
    SELECT * FROM kspots WHERE id=$id
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Titik Keliling</h4>

    <form method="POST" action="kspot_update.php">
        <input type="hidden" name="id"
               value="<?= $kspot['id']; ?>">

        <div class="form-group">
            <label>Nama Titik Keliling</label>
            <input type="text" name="name"
                   class="form-control"
                   value="<?= $kspot['name']; ?>"
                   required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="kspot_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
