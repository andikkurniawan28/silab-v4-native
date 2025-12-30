<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Flow</h4>

    <div class="row">

        <!-- ================= FORM (COL 4) ================= -->
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-2">
                    <strong>Input Flow</strong>
                </div>
                <div class="card-body">

                    <?php $current_hour = date('H') - 1; ?>

                    <form method="POST" action="balance_proses.php" class="text-dark">

                        <!-- Tanggal -->
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control"
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Jam -->
                        <div class="form-group">
                            <label>Jam</label>
                            <select class="form-control" name="created_at">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($current_hour == $i) ? 'selected' : '' ?>>
                                        <?= $i ?>:00 - <?= $i + 1 ?>:00
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <!-- Tebu -->
                        <div class="form-group">
                            <label>Tebu</label>
                            <input type="number" step="any" name="tebu" class="form-control" required>
                        </div>

                        <!-- Totalizer Pemurnian -->
                        <div class="form-group">
                            <label>Totalizer Pemurnian</label>
                            <input type="number" step="any" name="totalizer" class="form-control" required>
                        </div>

                        <!-- Totalizer Gilingan -->
                        <div class="form-group">
                            <label>Totalizer Gilingan</label>
                            <input type="number" step="any" name="totalizer_gilingan" class="form-control" required>
                        </div>

                        <!-- Decanter 1st -->
                        <div class="form-group">
                            <label>Totalizer Decanter 1<sup>st</sup></label>
                            <input type="number" step="any" name="totalizer_decanter_1st" class="form-control" required>
                        </div>

                        <!-- Decanter 2nd -->
                        <div class="form-group">
                            <label>Totalizer Decanter 2<sup>nd</sup></label>
                            <input type="number" step="any" name="totalizer_decanter_2nd" class="form-control" required>
                        </div>

                        <!-- SFC -->
                        <div class="form-group">
                            <label>SFC</label>
                            <input type="number" step="any" name="sfc" class="form-control" required>
                        </div>

                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? 0 ?>">

                        <button class="btn btn-primary btn-block">
                            Simpan
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <!-- ================= TABLE (COL 8) ================= -->
        <div class="col-md-9">
            <div class="card shadow mb-4">
                <div class="card-header py-2">
                    <strong>Data Flow</strong>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="balanceTable" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Timestamp</th>
                                    <th>Tebu</th>
                                    <th>Totalizer Pemurnian</th>
                                    <th>Totalizer Gilingan</th>
                                    <th>Totalizer Decanter 1<sup>st</sup></th>
                                    <th>Totalizer Decanter 2<sup>nd</sup></th>
                                    <th>Flow NM Pemurnian</th>
                                    <th>Flow NM Gilingan</th>
                                    <th>Flow Decanter 1<sup>st</sup></th>
                                    <th>Flow Decanter 2<sup>nd</sup></th>
                                    <th>NM % Tebu Pemurnian</th>
                                    <th>NM % Tebu Gilingan</th>
                                    <th>SFC</th>
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
$('#balanceTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: 'balance_fetch.php',
        type: 'POST'
    },
    order: [[0, 'desc']],
    columns: [
        {data:'id'},
        {data:'created_at'},
        {data:'tebu'},
        {data:'totalizer'},
        {data:'totalizer_gilingan'},
        {data:'totalizer_d1'},
        {data:'totalizer_d2'},
        {data:'flow_nm'},
        {data:'flow_nm_gilingan'},
        {data:'flow_decanter_1st'},
        {data:'flow_decanter_2nd'},
        {data:'nm_persen_tebu'},
        {data:'nm_persen_tebu_gilingan'},
        {data:'sfc'},
        {data:'action', orderable:false}
    ]
});
</script>
