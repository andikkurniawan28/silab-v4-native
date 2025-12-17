<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Titik Keliling</h4>

    <a href="kspot_tambah.php" class="btn btn-primary mb-3">
        Tambah Titik Keliling
    </a>

    <div class="table-responsive">
        <table id="kspotTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Titik Keliling</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#kspotTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'kspot_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});

function deleteKspot(id){
    if(confirm('Yakin hapus titik keliling ini?')){
        $.post('kspot_delete.php', {id:id}, function(){
            $('#kspotTable').DataTable().ajax.reload();
        });
    }
}
</script>
