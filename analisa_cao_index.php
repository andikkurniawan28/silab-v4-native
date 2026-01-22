<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Analisa CaO</h4>

    <div class="row">

        <!-- LEFT : FORM -->
        <div class="col-md-3">
            <form method="POST" action="analisa_cao_proses.php">

                <div class="form-group">
                    <label>Barcode</label>
                    <input type="number" name="sample_id" class="form-control" autofocus required>
                </div>

                <div class="form-group">
                    <label>Volume Titrasi</label>
                    <input type="number" step="0.01" name="volume_titrasi" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Pengenceran</label>
                    <input type="number" step="0.01" name="pengenceran" class="form-control" required>
                </div>

                <button class="btn btn-primary btn-block">
                    Simpan
                </button>
            </form>
        </div>

        <!-- RIGHT : TABLE -->
        <div class="col-md-9">
            <table id="caoTable"
                   class="table table-bordered table-striped text-dark"
                   width="100%">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Timestamp</th>
                        <th>Material</th>
                        <th>CaO</th>
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
    $('#caoTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'analisa_cao_fetch.php',
            type: 'POST'
        },
        order: [[0,'desc']],
        columns: [
            { data:'id' },
            { data:'created_at' },
            { data:'material' },
            { data:'cao' },
            { data:'action', orderable:false }
        ]
    });
});
</script>
