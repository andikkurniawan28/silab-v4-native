<?php include('header.php'); ?>

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

    $('#addForm').submit(function(e){
        e.preventDefault();
        $.post('user_store.php', $(this).serialize(), function(){
            $('#addModal').modal('hide');
            table.ajax.reload();
        });
    });

});

function deleteUser(id){
    if(confirm('Hapus user ini?')){
        $.post('user_delete.php', {id:id}, function(){
            $('#userTable').DataTable().ajax.reload();
        });
    }
}
</script>
