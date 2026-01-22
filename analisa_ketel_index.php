<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Analisa Ketel</h4>

    <div class="row">

        <!-- LEFT : FORM -->
        <div class="col-md-3">
            <form method="POST" action="analisa_ketel_proses.php">

                <div class="form-group">
                    <label>Barcode</label>
                    <input type="number" name="sample_id" class="form-control" autofocus required>
                </div>

                <div class="form-group">
                    <label>pH</label>
                    <input type="number" step="0.01" name="pH" class="form-control">
                </div>

                <div class="form-group">
                    <label>TDS</label>
                    <input type="number" step="0.01" name="TDS" class="form-control">
                </div>

                <div class="form-group">
                    <label>Sadah</label>
                    <input type="number" step="0.01" name="Sadah" class="form-control">
                </div>

                <div class="form-group">
                    <label>P2O5</label>
                    <input type="number" step="0.01" name="P2O5" class="form-control">
                </div>

                <div class="form-group">
                    <label>Silika</label>
                    <input type="number" step="0.01" name="Silika" class="form-control">
                </div>

                <button class="btn btn-primary btn-block">
                    Simpan
                </button>
            </form>
        </div>

        <!-- RIGHT : TABLE -->
        <div class="col-md-9">
            <table id="ketelTable"
                   class="table table-bordered table-striped text-dark"
                   width="100%">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Timestamp</th>
                        <th>Material</th>
                        <th>pH</th>
                        <th>TDS</th>
                        <th>Sadah</th>
                        <th>P2O5</th>
                        <th>Silika</th>
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
    $('#ketelTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0,'desc']],
        ajax: {
            url: 'analisa_ketel_fetch.php',
            type: 'POST'
        },
        columns: [
            { data:'id' },
            { data:'created_at' },
            { data:'material' },
            { data:'pH' },
            { data:'TDS' },
            { data:'Sadah' },
            { data:'P2O5' },
            { data:'Silika' },
            { data:'action', orderable:false }
        ]
    });
});
</script>
