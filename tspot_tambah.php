<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Titik Taksasi</h4>

    <form method="POST" action="tspot_store.php">
        <div class="form-group">
            <label>Nama Titik Taksasi</label>
            <input type="text" name="name"
                   class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="tspot_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
