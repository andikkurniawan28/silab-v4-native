<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    // 'Kabag', 
    // 'Kasie', 
    // 'Kasubsie', 
    // 'Admin QC', 
    // 'Koordinator QC', 
    'Mandor Off Farm', 
    'Analis Off Farm', 
    // 'Mandor On Farm', 
    // 'Analis On Farm', 
    // 'Operator Pabrikasi',
    // 'Staff Teknik',
    // 'Staff Tanaman',
    // 'Staff TUK',
    // 'Direksi',
    // 'Tamu',
    ]);
include('header_rev.php'); 

?>

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

    <h4 class="mb-3">Cetak Barcode <?= htmlspecialchars($station); ?></h4>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text"
                    id="materialSearch"
                    class="form-control"
                    placeholder="Cari material...">
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <?php foreach ($materials as $material): ?>
            <div class="col-lg-3 col-md-3 mb-4 material-card" data-name="<?= strtolower($material['name']) ?>">
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

                            <?php if (in_array($material['id'], [43, 44, 45, 46, 47, 48, 49])) : ?>

                                <div class="mb-2">
                                    <label for="pan" class="form-label">Pan</label>
                                    <input type="number" min="1" max="18" name="pan" id="pan" value="" class="form-control" required >
                                </div>

                                <div class="mb-2">
                                    <label for="hl" class="form-label">HL</label>
                                    <input type="number" name="volume" id="hl" value="" class="form-control" required>
                                </div>

                            <?php endif; ?>

                            <button type="submit"
                                    class="btn btn-warning btn-sm text-dark"
                            >
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

<script>
document.getElementById('materialSearch').addEventListener('keyup', function () {
    let keyword = this.value.toLowerCase();
    let cards = document.querySelectorAll('.material-card');

    cards.forEach(function (card) {
        let name = card.getAttribute('data-name');

        if (name.includes(keyword)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

<?php include('footer.php'); ?>
