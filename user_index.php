<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">User</h4>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">
        Tambah
    </button>

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

<!-- Modal Tambah -->
<div class="modal fade" id="addModal">
  <div class="modal-dialog">
    <form id="addForm">
      <div class="modal-content">
        <div class="modal-header">
            <h5>Tambah User</h5>
        </div>
        <div class="modal-body">
            <input type="text" name="name" class="form-control mb-2" placeholder="Nama" required>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
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
