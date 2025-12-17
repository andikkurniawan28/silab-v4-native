<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Role</h4>

    <a href="role_tambah.php" class="btn btn-primary mb-3">
        Tambah Role
    </a>

    <div class="table-responsive">
    <table id="roleTable" class="table table-bordered table-striped text-dark" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Role</th>
                <th>Fitur</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#roleTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'role_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'fitur' },
            { data: 'action', orderable:false }
        ]
    });
});

function deleteRole(id){
    if(confirm('Yakin ingin menghapus role ini?')){
        $.post('role_delete.php', {id:id}, function(){
            $('#roleTable').DataTable().ajax.reload();
        });
    }
}
</script>
