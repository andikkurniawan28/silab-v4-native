<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Product 50</h4>

    <div class="row">

        <!-- ================= FORM ================= -->
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-2">
                    <strong>Input Product 50</strong>
                </div>
                <div class="card-body">

                    <?php $current_hour = date('H') - 1; ?>

                    <form method="POST" action="product50_proses.php" class="text-dark">

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

                        <!-- C1 -->
                        <div class="form-group">
                            <label>C1 (Karung)</label>
                            <input type="number" name="cronus_1" class="form-control" required>
                        </div>

                        <!-- C2 -->
                        <div class="form-group">
                            <label>C2 (Karung)</label>
                            <input type="number" name="cronus_2" class="form-control" required>
                        </div>

                        <!-- C3 -->
                        <div class="form-group">
                            <label>C3 (Karung)</label>
                            <input type="number" name="cronus_3" class="form-control" required>
                        </div>

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
                    <strong>Data Product 50</strong>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="product50Table" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Timestamp</th>
                                    <th>C1 (Karung)</th>
                                    <th>C2 (Karung)</th>
                                    <th>C3 (Karung)</th>
                                    <th>Total (Ku)</th>
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
$('#product50Table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: 'product50_fetch.php',
        type: 'POST'
    },
    order: [[0, 'desc']],
    columns: [
        {data: 'id'},
        {data: 'created_at'},
        {data: 'cronus_1'},
        {data: 'cronus_2'},
        {data: 'cronus_3'},
        {data: 'value'},
        {data: 'action', orderable: false}
    ]
});
</script>
