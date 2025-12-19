<?php
include('header.php');

if (!isset($_GET['id'])) {
    die('ID analisa tidak ditemukan');
}

$id = $_GET['id'];

/**
 * Ambil data analisa + material
 */
$q = $conn->query("
    SELECT a.*, m.name AS material_name
    FROM analisa_off_farm_new a
    JOIN materials m ON m.id = a.material_id
    WHERE a.id = '$id'
    LIMIT 1
");

if ($q->num_rows == 0) {
    die('Data analisa tidak ditemukan');
}

$analisa = $q->fetch_assoc();

/**
 * Ambil indicator berdasarkan material (via methods)
 */
$indicators = $conn->query("
    SELECT i.*
    FROM indicators i
    ORDER BY i.id ASC
");
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Analisa</h4>

    <form method="POST" action="analisa_update.php">

        <div class="form-group">
            <label>Barcode</label>
            <input type="text"
                   name="id"
                   class="form-control"
                   value="<?= htmlspecialchars($analisa['id']); ?>"
                   readonly>
        </div>

        <div class="form-group">
            <label>Material</label>
            <input type="text"
                   class="form-control"
                   value="<?= htmlspecialchars($analisa['material_name']); ?>"
                   readonly>
        </div>

        <div class="form-group">
            <label>Hasil Analisa</label>
            <div class="border p-3">
                <div class="row">
                    <?php while ($i = $indicators->fetch_assoc()): ?>
                        <?php
                            $column = ucwords(str_replace(' ', '_', $i['name']));
                            $value  = $analisa[$column] ?? '';
                        ?>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label class="font-weight-bold">
                                <?= $i['name']; ?>
                            </label>
                            <input type="text"
                                   name="indicator[<?= $column; ?>]"
                                   class="form-control"
                                   value="<?= htmlspecialchars($value); ?>">
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="analisa_index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('footer.php'); ?>
