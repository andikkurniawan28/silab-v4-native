<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Imbibisi</h4>

    <div class="row">

        <!-- ================= FORM (COL 4) ================= -->
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-2">
                    <strong>Input Imbibisi</strong>
                </div>
                <div class="card-body">

                    <?php $current_hour = date('H') - 1; ?>

                    <form method="POST" action="imbibition_proses.php" class="text-dark">

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

                        <!-- Totalizer Pemurnian -->
                        <div class="form-group">
                            <label>Totalizer</label>
                            <input type="number" step="any" name="totalizer" class="form-control" required>
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
                    <strong>Data Imbibisi</strong>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="imbibitionTable" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Timestamp</th>
                                    <th>Totalizer</th>
                                    <th>Flow</th>
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
$('#imbibitionTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: 'imbibition_fetch.php',
        type: 'POST'
    },
    order: [[0, 'desc']],
    columns: [
        {data:'id'},
        {data:'created_at'},
        {data:'totalizer'},
        {data:'flow_imb'},
        {data:'action', orderable:false}
    ]
});
</script>
