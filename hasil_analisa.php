<?php include('header.php'); ?>

<?php
// 1. Ambil station_id
$station_id = $_GET['station_id'] ?? null;

if (!$station_id) {
    echo '<div class="alert alert-danger">Station tidak valid.</div>';
    include('footer.php');
    exit;
}

// 2. Ambil nama station
$station_name = '';
$stmtStation = mysqli_prepare($conn, "SELECT name FROM stations WHERE id = ?");
mysqli_stmt_bind_param($stmtStation, "i", $station_id);
mysqli_stmt_execute($stmtStation);
$resultStation = mysqli_stmt_get_result($stmtStation);

if ($rowStation = mysqli_fetch_assoc($resultStation)) {
    $station_name = $rowStation['name'];
}

// 3. Ambil data materials berdasarkan station_id
$materials = [];
$stmtMaterial = mysqli_prepare(
    $conn,
    "SELECT id, name FROM materials WHERE station_id = ? ORDER BY id ASC"
);
mysqli_stmt_bind_param($stmtMaterial, "i", $station_id);
mysqli_stmt_execute($stmtMaterial);
$resultMaterial = mysqli_stmt_get_result($stmtMaterial);

while ($row = mysqli_fetch_assoc($resultMaterial)) {
    $materials[] = $row;
}
?>

<!-- VIEW -->
<div class="container-fluid">
        
    <h4 class="mb-3">Hasil Analisa <?= htmlspecialchars($station_name); ?></h4>

    <div class="row">

        <?php if (count($materials) > 0): ?>
            <?php foreach ($materials as $material): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card bg-dark text-white text-sm shadow">
                        <div class="card-body">

                            <div class="font-weight-bold text-light text-uppercase mb-1">
                                <a href="hasil_analisa_detail.php?material_id=<?= $material['id']; ?>"
                                   class="text-light">
                                    <?= htmlspecialchars($material['name']); ?>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    Tidak ada material untuk station ini.
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include('footer.php'); ?>
