<?php include('header2.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Timbangan RS IN per Charge</h4>

    <a href="timbangan_rs_in_tambah.php" class="btn btn-primary mb-3">
        Tambah Timbangan RS IN per Charge
    </a>

    <div class="table-responsive">
        <table id="timbangan_rs_inTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Timestamp</th>
                    <th>Value</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#timbangan_rs_inTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
            url: 'timbangan_rs_in_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'created_at' },
            { data: 'value' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});
</script>
