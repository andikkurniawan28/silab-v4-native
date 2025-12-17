<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Stasiun</h4>

    <a href="station_tambah.php" class="btn btn-primary mb-3">
        Tambah Stasiun
    </a>

    <div class="table-responsive">
    <table id="stationTable" class="table table-bordered table-striped text-dark" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Stasiun</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#stationTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'station_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'action', orderable:false }
        ]
    });
});

function deleteStation(id){
    if(confirm('Yakin ingin menghapus stasiun ini?')){
        $.post('station_delete.php', {id:id}, function(){
            $('#stationTable').DataTable().ajax.reload();
        });
    }
}
</script>
