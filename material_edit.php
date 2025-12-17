<?php
include('header.php');

$id = $_GET['id'];

$material = $conn->query("
    SELECT * FROM materials WHERE id=$id
")->fetch_assoc();

$stations = $conn->query("SELECT * FROM stations");

$indicators = $conn->query("
    SELECT i.*, 
    IF(m.id IS NULL,0,1) AS checked
    FROM indicators i
    LEFT JOIN methods m 
      ON m.indicator_id=i.id AND m.material_id=$id
");
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Material</h4>

    <form method="POST" action="material_update.php">
        <input type="hidden" name="id" value="<?= $id; ?>">

        <div class="form-group">
            <label>Station</label>
            <select name="station_id" class="form-control" required>
                <?php while($s=$stations->fetch_assoc()): ?>
                    <option value="<?= $s['id']; ?>"
                        <?= $s['id']==$material['station_id']?'selected':'' ?>>
                        <?= $s['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Nama Material</label>
            <input type="text" name="name"
                   class="form-control"
                   value="<?= $material['name']; ?>" required>
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
                                    value="<?= $i['id']; ?>"
                                    <?= $i['checked'] ? 'checked' : ''; ?>>
                                <?= $i['name']; ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="material_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
