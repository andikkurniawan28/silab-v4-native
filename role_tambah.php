<?php
include('header.php');

/**
 * Ambil semua feature
 */
$features = $conn->query("
    SELECT id, judul
    FROM features
    ORDER BY judul ASC
");
?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Role</h4>

    <form method="POST" action="role_store.php">
        <div class="form-group">
            <label>Nama Role</label>
            <input type="text" name="name" class="form-control" required>
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
                               id="feature<?= $f['id']; ?>">
                        <label class="form-check-label"
                               for="feature<?= $f['id']; ?>">
                            <?= htmlspecialchars($f['judul']); ?>
                        </label>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <hr>

        <button class="btn btn-primary">Simpan</button>
        <a href="role_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
document.getElementById('checkAll').addEventListener('change', function () {
    const checked = this.checked;
    document.querySelectorAll('.feature-checkbox').forEach(cb => {
        cb.checked = checked;
    });
});

/**
 * Sinkronisasi:
 * kalau semua dicentang manual → checkAll ikut aktif
 */
document.querySelectorAll('.feature-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
        const all = document.querySelectorAll('.feature-checkbox');
        const checked = document.querySelectorAll('.feature-checkbox:checked');
        document.getElementById('checkAll').checked = (all.length === checked.length);
    });
});
</script>

<?php include('footer.php'); ?>
