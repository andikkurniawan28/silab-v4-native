<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Antrian Gelas ARI</h4>

    <div class="row">

        <!-- LEFT : FORM -->
        <div class="col-md-3">
            <form method="POST" action="antrian_gelas_ari_ek_proses.php">

                <div class="form-group">
                    <label>RFID</label>
                    <input type="text" name="rfid" class="form-control" maxlength="6" autofocus required>
                </div>

                <button class="btn btn-primary btn-block">
                    Submit
                </button>
                <a href="antrian_gelas_ari_ek_delete_all.php"
                    class="btn btn-danger btn-block"
                    onclick="return confirm('Yakin ingin menghapus SEMUA data?');">
                    Hapus Semua
                </a>
            </form>
        </div>

        <!-- RIGHT : TABLE -->
        <div class="col-md-9">
            <table id="ampasTable"
                   class="table table-bordered table-striped text-dark"
                   width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>RFID</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function(){
    $('#ampasTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'antrian_gelas_ari_ek_fetch.php',
            type: 'POST'
        },
        order: [[0,'desc']],
        columns: [
            { data:'id' },
            { data:'rfid' },
            { data:'action', orderable:false }
        ]
    });
});
</script>
