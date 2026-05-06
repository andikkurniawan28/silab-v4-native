
<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    'Kabag', 
    'Kasie', 
    'Kasubsie', 
    'Admin QC', 
    'Koordinator QC', 
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

<div class="container-fluid">
    <h4 class="mb-3">Produksi Retail</h4>

    <div class="row">

        <!-- ================= FORM ================= -->
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-2">
                    <strong>Input Produksi Retail</strong>
                </div>
                <div class="card-body">

                    <?php $current_hour = date('H') - 1; ?>

                    <form method="POST" action="retail_proses.php" class="text-dark">

                        <!-- Tanggal -->
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Jam -->
                        <div class="form-group">
                            <label>Jam</label>
                            <select class="form-control" name="jam">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($current_hour == $i) ? 'selected' : '' ?>>
                                        <?= $i ?>:00 - <?= $i + 1 ?>:00
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <style>
.checkbox-lg .form-check-input {
    width: 22px;
    height: 22px;
    margin-top: 0.2rem;
}

.checkbox-lg .form-check-label {
    font-size: 18px;
    margin-left: 4px;
}
</style>

                        <!-- Checkbox A sampai F -->
                        <div class="form-group">
                            <label>Mesin Aktif</label><br>
                            <div class="form-check form-check-inline checkbox-lg">
                                <input class="form-check-input" type="checkbox" name="mesin_aktif[]" value="A">
                                <label class="form-check-label">A</label>
                            </div>
                            <div class="form-check form-check-inline checkbox-lg">
                                <input class="form-check-input" type="checkbox" name="mesin_aktif[]" value="B">
                                <label class="form-check-label">B</label>
                            </div>
                            <div class="form-check form-check-inline checkbox-lg">
                                <input class="form-check-input" type="checkbox" name="mesin_aktif[]" value="C">
                                <label class="form-check-label">C</label>
                            </div>
                            <div class="form-check form-check-inline checkbox-lg">
                                <input class="form-check-input" type="checkbox" name="mesin_aktif[]" value="D">
                                <label class="form-check-label">D</label>
                            </div>
                            <div class="form-check form-check-inline checkbox-lg">
                                <input class="form-check-input" type="checkbox" name="mesin_aktif[]" value="E">
                                <label class="form-check-label">E</label>
                            </div>
                            <div class="form-check form-check-inline checkbox-lg">
                                <input class="form-check-input" type="checkbox" name="mesin_aktif[]" value="F">
                                <label class="form-check-label">F</label>
                            </div>
                        </div>

                        <!-- Total (Value) -->
                        <div class="form-group">
                            <label>Total (Kg)</label>
                            <input type="number" name="value" class="form-control" step="0.01" required>
                        </div>

                        <!-- berat_a -->
                        <div class="form-group">
                            <label><?= ucwords('berat_a') ?></label>
                            <input type="number" name="berat_a" class="form-control" step="0.01">
                        </div>

                        <!-- berat_b -->
                        <div class="form-group">
                            <label><?= ucwords('berat_b') ?></label>
                            <input type="number" name="berat_b" class="form-control" step="0.01">
                        </div>

                        <!-- berat_c -->
                        <div class="form-group">
                            <label><?= ucwords('berat_c') ?></label>
                            <input type="number" name="berat_c" class="form-control" step="0.01">
                        </div>

                        <!-- berat_d -->
                        <div class="form-group">
                            <label><?= ucwords('berat_d') ?></label>
                            <input type="number" name="berat_d" class="form-control" step="0.01">
                        </div>

                        <!-- berat_e -->
                        <div class="form-group">
                            <label><?= ucwords('berat_e') ?></label>
                            <input type="number" name="berat_e" class="form-control" step="0.01">
                        </div>

                        <!-- berat_f -->
                        <div class="form-group">
                            <label><?= ucwords('berat_f') ?></label>
                            <input type="number" name="berat_f" class="form-control" step="0.01">
                        </div>

                        <!-- berat_f -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="expired_checked" id="expired_checked" value="1">
                            <label class="form-check-label" for="expired_checked">
                                Expired Ada
                            </label>
                        </div>

                        <br><br>

                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? 0 ?>">

                        <button class="btn btn-primary btn-block">
                            Simpan
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <!-- ================= TABLE ================= -->
        <div class="col-md-9">
            <div class="card shadow mb-4">
                <div class="card-header py-2">
                    <strong>Data Produksi Retail</strong>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="retailTable" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Timestamp</th>
                                    <!-- <th>Tanggal</th>
                                    <th>Jam</th> -->
                                    <th>Mesin Aktif</th>
                                    <th>Total (Kg)</th>
                                    <th>A</th>
                                    <th>B</th>
                                    <th>C</th>
                                    <th>D</th>
                                    <th>E</th>
                                    <th>F</th>
                                    <th>Expired</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<?php include('footer.php'); ?>

<script>
$('#retailTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: 'retail_fetch.php',
        type: 'POST'
    },
    order: [[0, 'desc']],
    columns: [
        {data: 'id'},
        {data: 'created_at'},
        // {data: 'tanggal'},
        // {data: 'jam'},
        {data: 'mesin_aktif'},
        {data: 'value'},
        {data: 'berat_a'},
        {data: 'berat_b'},
        {data: 'berat_c'},
        {data: 'berat_d'},
        {data: 'berat_e'},
        {data: 'berat_f'},
        {data: 'expired_checked'},
        {data: 'action', orderable: false}
    ]
});
</script>