<?php
include('header.php');

$id = $_GET['id'];

$user = $conn->query("
    SELECT * FROM users WHERE id = $id
")->fetch_assoc();

$roles = $conn->query("SELECT * FROM roles");
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit User</h4>

    <form method="POST" action="user_update.php">
        <input type="hidden" name="id" value="<?= $user['id']; ?>">

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control"
                   value="<?= $user['name']; ?>" required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?= $user['username']; ?>" required>
        </div>

        <div class="form-group">
            <label>Password 
                <small class="text-muted">(kosongkan jika tidak diubah)</small>
            </label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role_id" class="form-control" required>
                <?php while($r = $roles->fetch_assoc()): ?>
                    <option value="<?= $r['id']; ?>"
                        <?= $r['id']==$user['role_id']?'selected':'' ?>>
                        <?= $r['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1" <?= $user['is_active']?'selected':'' ?>>Aktif</option>
                <option value="0" <?= !$user['is_active']?'selected':'' ?>>Nonaktif</option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="user_index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('footer.php'); ?>
