<?php
include('header.php');

$roles = $conn->query("SELECT * FROM roles");
?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah User</h4>

    <form method="POST" action="user_store.php">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password"
                class="form-control"
                required>
        </div>


        <div class="form-group">
            <label>Role</label>
            <select name="role_id" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <?php while($r = $roles->fetch_assoc()): ?>
                    <option value="<?= $r['id']; ?>">
                        <?= $r['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="user_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
