<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    'Kabag', 
    'Kasie', 
    'Kasubsie', 
    // 'Admin QC', 
    'Koordinator QC', 
    'Mandor Off Farm', 
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
    <h4 class="mb-3">Material</h4>

    <a href="material_tambah.php" class="btn btn-primary mb-3">
        Tambah Material
    </a>

    <div class="table-responsive">
        <table id="materialTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Stasiun</th>
                    <th>Nama Material</th>
                    <th>Indikator</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#materialTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'material_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'station' },
            { data: 'name' },
            { data: 'indicators' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});

function deleteMaterial(id){
    if(confirm('Yakin hapus material ini?')){
        $.post('material_delete.php', {id:id}, function(){
            $('#materialTable').DataTable().ajax.reload();
        });
    }
}
</script>
