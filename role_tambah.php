<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Role</h4>

    <form method="POST" action="role_store.php">
        <div class="form-group">
            <label>Nama Role</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="role_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
