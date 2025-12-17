<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Analisa</h4>

    <div class="table-responsive">
        <table id="analisaTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Material</th>
                    <th>Hasil Analisa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#analisaTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
            url: 'analisa_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'material' },
            { data: 'hasil_analisa', orderable:false, searchable:false },
            { data: 'status' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});
</script>
