<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Bahan Pembantu Proses</h4>

    <a href="chemical_tambah.php" class="btn btn-primary mb-3">
        Tambah Bahan Pembantu Proses
    </a>

    <div class="table-responsive">
        <table id="chemicalTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Bahan Pembantu Proses</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#chemicalTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'chemical_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});

function deletechemical(id){
    if(confirm('Yakin hapus Bahan Pembantu Proses ini?')){
        $.post('chemical_delete.php', {id:id}, function(){
            $('#chemicalTable').DataTable().ajax.reload();
        });
    }
}
</script>
