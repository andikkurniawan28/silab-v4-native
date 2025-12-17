<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Indikator</h4>

    <form method="POST" action="indicator_store.php">
        <div class="form-group">
            <label>Nama Indikator</label>
            <input type="text" name="name"
                   class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="indicator_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
