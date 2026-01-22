<?php
include('header.php');
?>

<div class="container-fluid">
    <h4 class="mb-3">Analisa SO₂</h4>

    <div class="row">

        <!-- FORM -->
        <div class="col-md-3">
            <form method="POST" action="analisa_so2_proses.php">

                <div class="form-group">
                    <label>Barcode</label>
                    <input type="number" name="sample_id" class="form-control" autofocus required>
                </div>

                <div class="form-group">
                    <label>V1</label>
                    <input type="number" step="0.01" name="v1" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>V2</label>
                    <input type="number" step="0.01" name="v2" class="form-control" required>
                </div>

                <button class="btn btn-primary btn-block">
                    Simpan
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="col-md-9">
            <table id="so2Table"
                   class="table table-bordered table-striped text-dark"
                   width="100%">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Timestamp</th>
                        <th>Material</th>
                        <th>SO₂</th>
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
    $('#so2Table').DataTable({
        processing: true,
        serverSide: true,
        order: [[0,'desc']],
        ajax: {
            url: 'analisa_so2_fetch.php',
            type: 'POST'
        },
        columns: [
            { data:'id' },
            { data:'created_at' },
            { data:'material' },
            { data:'SO2' },
            { data:'action', orderable:false }
        ]
    });
});
</script>
