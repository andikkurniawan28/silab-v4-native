<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Indikator</h4>

    <a href="indicator_tambah.php" class="btn btn-primary mb-3">
        Tambah Indikator
    </a>

    <div class="table-responsive">
        <table id="indicatorTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Indikator</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#indicatorTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'indicator_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});

function deleteIndicator(id){
    if(confirm('Yakin ingin menghapus indikator ini?')){
        $.post('indicator_delete.php', {id:id}, function(){
            $('#indicatorTable').DataTable().ajax.reload();
        });
    }
}
</script>
