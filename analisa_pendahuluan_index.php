
<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    'Kabag', 
    'Kasie', 
    'Kasubsie', 
    'Admin QC', 
    'Koordinator QC', 
    // 'Mandor Off Farm', 
    // 'Analis Off Farm', 
    'Mandor On Farm', 
    'Analis On Farm', 
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
    <h4 class="mb-3">Analisa Pendahuluan</h4>

    <div class="table-responsive">
    <table id="analisa_pendahuluanTable" class="table table-bordered table-striped text-dark" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Wilayah</th>
                <th>Timestamp</th>
                <th>Berat Tebu Atas</th>
                <th>Berat Nira Atas</th>
                <th>Brix Atas</th>
                <th>Pol Atas</th>
                <th>Rend Atas</th>
                <th>Berat Tebu Tengah</th>
                <th>Berat Nira Tengah</th>
                <th>Brix Tengah</th>
                <th>Pol Tengah</th>
                <th>Rend Tengah</th>
                <th>Berat Tebu Bawah</th>
                <th>Berat Nira Bawah</th>
                <th>Brix Bawah</th>
                <th>Pol Bawah</th>
                <th>Rend Bawah</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#analisa_pendahuluanTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0,'desc']],
        ajax: {
            url: 'analisa_pendahuluan_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'code' },
            { data: 'wilayah' },
            { data: 'timestamp' },
            { data: 'berat_tebu_atas' },
            { data: 'berat_nira_atas' },
            { data: 'brix_atas' },
            { data: 'pol_atas' },
            { data: 'rendemen_atas' },
            { data: 'berat_tebu_tengah' },
            { data: 'berat_nira_tengah' },
            { data: 'brix_tengah' },
            { data: 'pol_tengah' },
            { data: 'rendemen_tengah' },
            { data: 'berat_tebu_bawah' },
            { data: 'berat_nira_bawah' },
            { data: 'brix_bawah' },
            { data: 'pol_bawah' },
            { data: 'rendemen_bawah' },
            { data: 'action', orderable:false }
        ]
    });
});

function deleteStation(id){
    if(confirm('Yakin ingin menghapus data ini?')){
        $.post('analisa_pendahuluan_delete.php', {id:id}, function(){
            $('#analisa_pendahuluanTable').DataTable().ajax.reload();
        });
    }
}
</script>
