<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Timbangan Reject per Charge</h4>

    <a href="timbangan_reject_tambah.php" class="btn btn-primary mb-3">
        Tambah Timbangan Reject per Charge
    </a>

    <div class="table-responsive">
        <table id="timbangan_rejectTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Timestamp</th>
                    <th>Bruto</th>
                    <th>Tarra</th>
                    <th>Netto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#timbangan_rejectTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
            url: 'timbangan_reject_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'created_at' },
            { data: 'bruto' },
            { data: 'tarra' },
            { data: 'netto' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});
</script>
