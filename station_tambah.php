<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Stasiun</h4>

    <form method="POST" action="station_store.php">
        <div class="form-group">
            <label>Nama Stasiun</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="station_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
