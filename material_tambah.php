<?php
include('header.php');

$stations = $conn->query("SELECT * FROM stations");
$indicators = $conn->query("SELECT * FROM indicators");
?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Material</h4>

    <form method="POST" action="material_store.php">

        <div class="form-group">
            <label>Station</label>
            <select name="station_id" class="form-control" required>
                <option value="">-- Pilih Station --</option>
                <?php while($s=$stations->fetch_assoc()): ?>
                    <option value="<?= $s['id']; ?>">
                        <?= $s['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Nama Material</label>
            <input type="text" name="name"
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label>Indicator</label>
            <div class="border p-2">
                <div class="row">
                    <?php while($i = $indicators->fetch_assoc()): ?>
                        <div class="col-md-3 col-sm-6">
                            <label class="d-block">
                                <input type="checkbox"
                                    name="indicator_ids[]"
                                    value="<?= $i['id']; ?>">
                                <?= $i['name']; ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="material_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
