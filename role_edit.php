<?php
include('header.php');

$id = $_GET['id'];

$role = $conn->query("
    SELECT * FROM roles WHERE id = $id
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Role</h4>

    <form method="POST" action="role_update.php">
        <input type="hidden" name="id" value="<?= $role['id']; ?>">

        <div class="form-group">
            <label>Nama Role</label>
            <input type="text" name="name" class="form-control"
                   value="<?= $role['name']; ?>" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="role_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
