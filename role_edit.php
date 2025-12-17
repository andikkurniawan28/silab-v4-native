<?php
include('header.php');
include('db.php');

$id = intval($_GET['id']);

/**
 * Ambil role
 */
$role = $conn->query("
    SELECT * FROM roles WHERE id = $id
")->fetch_assoc();

if (!$role) {
    die('Role tidak ditemukan');
}

/**
 * Ambil semua feature
 */
$features = $conn->query("
    SELECT id, judul
    FROM features
    ORDER BY judul ASC
");

/**
 * Ambil permission role ini
 */
$permissions = [];
$res = $conn->query("
    SELECT feature_id
    FROM permissions
    WHERE role_id = $id
");

while ($p = $res->fetch_assoc()) {
    $permissions[] = $p['feature_id'];
}
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Role</h4>

    <form method="POST" action="role_update.php">
        <input type="hidden" name="id" value="<?= $role['id']; ?>">

        <div class="form-group">
            <label>Nama Role</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="<?= htmlspecialchars($role['name']); ?>"
                   required>
        </div>

        <hr>
        <h5 class="mb-2">Hak Akses Fitur</h5>

        <!-- CHECK ALL -->
        <div class="form-check mb-3">
            <input type="checkbox"
                   class="form-check-input"
                   id="checkAll">
            <label class="form-check-label font-weight-bold"
                   for="checkAll">
                Pilih Semua Fitur
            </label>
        </div>

        <div class="row">
            <?php while($f = $features->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="form-check mb-2">
                        <input type="checkbox"
                               class="form-check-input feature-checkbox"
                               name="features[]"
                               value="<?= $f['id']; ?>"
                               id="feature<?= $f['id']; ?>"
                               <?= in_array($f['id'], $permissions) ? 'checked' : ''; ?>>
                        <label class="form-check-label"
                               for="feature<?= $f['id']; ?>">
                            <?= htmlspecialchars($f['judul']); ?>
                        </label>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <hr>

        <button class="btn btn-primary">Update</button>
        <a href="role_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
const checkAll = document.getElementById('checkAll');
const featureCheckboxes = document.querySelectorAll('.feature-checkbox');

/**
 * Inisialisasi:
 * kalau semua fitur sudah dicentang → checkAll aktif
 */
function syncCheckAll() {
    const total = featureCheckboxes.length;
    const checked = document.querySelectorAll('.feature-checkbox:checked').length;
    checkAll.checked = (total === checked && total > 0);
}
syncCheckAll();

/**
 * Klik check all
 */
checkAll.addEventListener('change', function () {
    featureCheckboxes.forEach(cb => cb.checked = this.checked);
});

/**
 * Klik individual checkbox
 */
featureCheckboxes.forEach(cb => {
    cb.addEventListener('change', syncCheckAll);
});
</script>

<?php include('footer.php'); ?>
