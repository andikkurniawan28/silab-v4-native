<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    // 'Kabag', 
    // 'Kasie', 
    // 'Kasubsie', 
    // 'Admin QC', 
    // 'Koordinator QC', 
    // 'Mandor Off Farm', 
    // 'Analis Off Farm', 
    // 'Mandor On Farm', 
    // 'Analis On Farm', 
    // 'Operator Pabrikasi',
    // 'Staff Teknik',
    // 'Staff Tanaman',
    // 'Staff TUK',
    // 'Direksi',
    // 'Tamu',
    ]);
include('header_rev.php');

?>

<div class="container-fluid">
    <h4 class="mb-3">Faktor</h4>

    <a href="factor_tambah.php" class="btn btn-primary mb-3">
        Tambah Faktor
    </a>

    <div class="table-responsive">
        <table id="factorTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Faktor</th>
                    <th>Value</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#factorTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'factor_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'value' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});

function deleteFactor(id){
    if(confirm('Yakin ingin menghapus faktor ini?')){
        $.post('factor_delete.php', {id:id}, function(){
            $('#factorTable').DataTable().ajax.reload();
        });
    }
}
</script>
