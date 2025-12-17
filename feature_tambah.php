<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Fitur</h4>

    <form method="POST" action="feature_store.php">
        <div class="form-group">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label>Filename</label>
            <input type="text" name="filename"
                   class="form-control"
                   placeholder="contoh: user_index.php"
                   required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="feature_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
