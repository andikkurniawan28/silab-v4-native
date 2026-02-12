<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    'Kabag', 
    'Kasie', 
    'Kasubsie', 
    'Admin QC', 
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
    <h4 class="mb-3">Penggunaan Bahan Pembantu Proses</h4>

    <a href="penggunaan_bpp_tambah.php" class="btn btn-primary mb-3">
        Tambah Data
    </a>

    <div class="table-responsive">
        <table id="kelilingProsesTable" class="table table-bordered table-striped text-dark" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Timestamp</th>
                    <th>Hasil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#kelilingProsesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'penggunaan_bpp_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'created_at' },
            { data: 'hasil' },
            { data: 'action', orderable: false }
        ],
        order: [[1, 'desc']] // Default sort by created_at descending
    });
});
</script>