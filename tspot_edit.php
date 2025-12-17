<?php
include('header.php');

$id = $_GET['id'];

$tspot = $conn->query("
    SELECT * FROM tspots WHERE id=$id
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Titik Taksasi</h4>

    <form method="POST" action="tspot_update.php">
        <input type="hidden" name="id"
               value="<?= $tspot['id']; ?>">

        <div class="form-group">
            <label>Nama Titik Taksasi</label>
            <input type="text" name="name"
                   class="form-control"
                   value="<?= $tspot['name']; ?>"
                   required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="tspot_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
