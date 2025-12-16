<?php include('header.php'); ?>

<?php
// ================= DATA =================

// 1. Ambil station_id
$station_id = $_GET['station_id'] ?? null;

if (!$station_id) {
    echo '<div class="alert alert-danger">Station tidak valid.</div>';
    include('footer.php');
    exit;
}

// 2. Ambil nama station
$station = '';
$stmt = mysqli_prepare($conn, "SELECT name FROM stations WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $station_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $station = $row['name'];
}

// 3. Ambil materials berdasarkan station_id
$materials = [];
$stmt = mysqli_prepare($conn, "
    SELECT id, name 
    FROM materials 
    WHERE station_id = ?
    ORDER BY id ASC
");
mysqli_stmt_bind_param($stmt, "i", $station_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $materials[] = $row;
}
?>

<!-- ================= VIEW ================= -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="m-0 font-weight-bold text-primary">
            Cetak Barcode <?= htmlspecialchars($station); ?>
        </h4>
    </div>

    <!-- Content Row -->
    <div class="row">

        <?php foreach ($materials as $material): ?>
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card bg-dark text-white text-xs shadow">
                    <div class="card-body">

                        <div class="font-weight-bold text-light text-uppercase mb-2">
                            <?= htmlspecialchars($material['name']); ?>
                        </div>

                        <form action="cetak_barcode_proses.php"
                              method="POST"
                              class="form-prevent">

                            <input type="hidden" name="material_id"
                                   value="<?= $material['id']; ?>">

                            <button type="submit"
                                    class="btn btn-warning btn-sm text-dark"
                                    onclick="this.form.submit(); this.disabled=true;">
                                Cetak
                                <i class="fas fa-print"></i>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (count($materials) === 0): ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    Tidak ada material pada station ini.
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<?php include('footer.php'); ?>
