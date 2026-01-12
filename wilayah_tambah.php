<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Wilayah</h4>

    <form method="POST" action="wilayah_store.php">

        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="code" class="form-control" autofocus required>
        </div>

        <div class="form-group">
            <label>Nama Wilayah</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="wilayah_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
