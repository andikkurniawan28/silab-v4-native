<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Faktor</h4>

    <form method="POST" action="factor_store.php">
        <div class="form-group">
            <label>Nama Faktor</label>
            <input type="text" name="name"
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label>Value</label>
            <input type="number" step="0.01"
                   name="value"
                   class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="factor_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
