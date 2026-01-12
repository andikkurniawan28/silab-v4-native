<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Wilayah</h4>

    <a href="wilayah_tambah.php" class="btn btn-primary mb-3">
        Tambah Wilayah
    </a>

    <div class="table-responsive">
    <table id="wilayahTable" class="table table-bordered table-striped text-dark" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama Wilayah</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#wilayahTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'wilayah_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'code' },
            { data: 'name' },
            { data: 'action', orderable:false }
        ]
    });
});

function deleteStation(id){
    if(confirm('Yakin ingin menghapus stasiun ini?')){
        $.post('wilayah_delete.php', {id:id}, function(){
            $('#wilayahTable').DataTable().ajax.reload();
        });
    }
}
</script>
