<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Uji Karung</h4>

    <div class="row">

        <!-- ================= TABLE ================= -->
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-2 d-flex justify-content-between align-items-center">
                    <strong>Data Uji Karung</strong>
                    <a href="uji_karung_create.php" class="btn btn-primary btn-sm">
                        + Tambah Uji Karung
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ujiKarungTable" class="table table-bordered table-striped table-sm" width="100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal Uji</th>
                                    <th>Kedatangan</th>
                                    <th>Batch</th>
                                    <th>No</th>
                                    <th>Denier</th>
                                    <th>Status</th>
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
$('#ujiKarungTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: 'uji_karung_fetch.php',
        type: 'POST'
    },
    order: [[0, 'desc']],
    columns: [
        { data: 'id' },
        { data: 'tanggal' },
        { data: 'kedatangan' },
        { data: 'batch' },
        { data: 'nomor' },
        { data: 'denier_nilai' },
        { data: 'status' },
        { data: 'action', orderable: false }
    ]
});
</script>
