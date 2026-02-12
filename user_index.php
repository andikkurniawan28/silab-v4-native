
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
    <h4 class="mb-3">User</h4>

    <a href="user_tambah.php" class="btn btn-primary mb-3">
        Tambah User
    </a>

    <div class="table-responsive">
    <table id="userTable" class="table table-bordered table-striped text-dark" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Role</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {

    let table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'user_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'role' },
            { data: 'name' },
            { data: 'username' },
            { data: 'is_active' },
            { data: 'action', orderable:false }
        ]
    });

});
</script>
