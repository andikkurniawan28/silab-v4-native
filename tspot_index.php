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
    <h4 class="mb-3">Titik Taksasi</h4>

    <a href="tspot_tambah.php" class="btn btn-primary mb-3">
        Tambah Titik Taksasi
    </a>

    <div class="table-responsive">
        <table id="tspotTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Titik Taksasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#tspotTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'tspot_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});

function deletetspot(id){
    if(confirm('Yakin hapus titik Taksasi ini?')){
        $.post('tspot_delete.php', {id:id}, function(){
            $('#tspotTable').DataTable().ajax.reload();
        });
    }
}
</script>
