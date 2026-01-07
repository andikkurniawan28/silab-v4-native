<?php include('header2.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Timbangan RS IN per Charge</h4>

    <form method="POST" action="timbangan_rs_in_store.php">
        <div class="form-group">
            <label>Timestamp</label>
            <input type="text" id="created_at" name="created_at"
                    value="<?= date('Y-m-d H:i'); ?>"
                    class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Value</label>
            <input type="number" step="0.01" id="value" name="value"
                   class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="timbangan_rs_in_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
