<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Titik Keliling</h4>

    <form method="POST" action="kspot_store.php">
        <div class="form-group">
            <label>Nama Titik Keliling</label>
            <input type="text" name="name"
                   class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="kspot_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
