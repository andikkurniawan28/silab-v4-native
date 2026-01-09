<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">ARI 1</h4>

    <div class="table-responsive">
    <table id="ari1Table" class="table table-bordered table-striped text-dark" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Gelas</th>
                <th>Timestamp</th>
                <th>Antrian</th>
                <th>Brix</th>
                <th>Pol</th>
                <th>Z</th>
                <th>R</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#ari1Table').DataTable({
        processing: true,
        serverSide: true,
        order: [[0,'desc']],
        ajax: {
            url: 'ari1_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'gelas' },
            { data: 'timestamp' },
            { data: 'nomor_antrian' },
            { data: 'brix_core' },
            { data: 'pol_core' },
            { data: 'pol_baca_core' },
            { data: 'rendemen_core' },
            { data: 'action', orderable:false }
        ]
    });
});

function deleteStation(id){
    if(confirm('Yakin ingin menghapus data ini?')){
        $.post('ari1_delete.php', {id:id}, function(){
            $('#ari1Table').DataTable().ajax.reload();
        });
    }
}
</script>
