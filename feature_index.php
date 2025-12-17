<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Fitur</h4>

    <a href="feature_tambah.php" class="btn btn-primary mb-3">
        Tambah Fitur
    </a>

    <div class="table-responsive">
        <table id="featureTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Filename</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#featureTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'feature_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'judul' },
            { data: 'deskripsi' },
            { data: 'filename' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});

function deleteFeature(id){
    if(confirm('Yakin hapus fitur ini?')){
        $.post('feature_delete.php', {id:id}, function(){
            $('#featureTable').DataTable().ajax.reload();
        });
    }
}
</script>
