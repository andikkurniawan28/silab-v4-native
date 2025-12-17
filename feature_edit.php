<?php
include('header.php');
include('db.php');

$id = intval($_GET['id']);
$data = $conn->query("
    SELECT * FROM features WHERE id='$id'
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Fitur</h4>

    <form method="POST" action="feature_update.php">
        <input type="hidden" name="id" value="<?= $data['id']; ?>">

        <div class="form-group">
            <label>Judul</label>
            <input type="text" name="judul"
                   class="form-control"
                   value="<?= htmlspecialchars($data['judul']); ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi"
                      class="form-control"
                      rows="3"><?= htmlspecialchars($data['deskripsi']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Filename</label>
            <input type="text" name="filename"
                   class="form-control"
                   value="<?= htmlspecialchars($data['filename']); ?>"
                   required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="feature_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
