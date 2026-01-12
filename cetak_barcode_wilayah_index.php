<?php include('header.php'); ?>

<?php

// 3. Ambil kuds
$kuds = [];
$stmt = mysqli_prepare($conn, "
    SELECT id, name, code
    FROM kuds 
    ORDER BY id ASC
");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $kuds[] = $row;
}
?>

<!-- ================= VIEW ================= -->
<div class="container-fluid">

    <h4 class="mb-3">Cetak Barcode Analisa Pendahuluan</h4>

    <!-- Content Row -->
    <div class="row">

        <?php foreach ($kuds as $kud): ?>
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card bg-secondary text-white text-xs shadow">
                    <div class="card-body">

                        <div class="font-weight-bold text-light text-uppercase mb-2">
                            <?= htmlspecialchars($kud['code']); ?> | 
                            <?= htmlspecialchars($kud['name']); ?>
                        </div>

                        <form action="cetak_barcode_wilayah_proses.php"
                              method="POST"
                              class="form-prevent">

                            <input type="hidden" name="kud_id"
                                   value="<?= $kud['id']; ?>">

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

        <?php if (count($kuds) === 0): ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    Tidak ada kud pada station ini.
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<?php include('footer.php'); ?>
