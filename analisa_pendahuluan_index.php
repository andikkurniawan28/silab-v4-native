<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Analisa Pendahuluan</h4>

    <div class="table-responsive">
    <table id="analisa_pendahuluanTable" class="table table-bordered table-striped text-dark" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Timestamp</th>
                <th>BA</th>
                <th>PA</th>
                <th>RA</th>
                <th>BT</th>
                <th>PT</th>
                <th>RT</th>
                <th>BB</th>
                <th>PB</th>
                <th>RB</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#analisa_pendahuluanTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0,'desc']],
        ajax: {
            url: 'analisa_pendahuluan_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'code' },
            { data: 'timestamp' },
            { data: 'brix_atas' },
            { data: 'pol_atas' },
            { data: 'rendemen_atas' },
            { data: 'brix_tengah' },
            { data: 'pol_tengah' },
            { data: 'rendemen_tengah' },
            { data: 'brix_bawah' },
            { data: 'pol_bawah' },
            { data: 'rendemen_bawah' },
            { data: 'action', orderable:false }
        ]
    });
});

function deleteStation(id){
    if(confirm('Yakin ingin menghapus data ini?')){
        $.post('analisa_pendahuluan_delete.php', {id:id}, function(){
            $('#analisa_pendahuluanTable').DataTable().ajax.reload();
        });
    }
}
</script>
