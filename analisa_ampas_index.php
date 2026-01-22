<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Analisa Ampas</h4>

    <div class="row">

        <!-- LEFT : FORM -->
        <div class="col-md-3">
            <form method="POST" action="analisa_ampas_proses.php">

                <div class="form-group">
                    <label>Barcode</label>
                    <input type="number" name="sample_id" class="form-control" autofocus required>
                </div>

                <div class="form-group">
                    <label>% Air</label>
                    <input type="number" step="0.01" name="air" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>% Zk</label>
                    <input type="number" step="0.01" name="zk" class="form-control" required>
                </div>

                <button class="btn btn-primary btn-block">
                    Submit
                </button>
            </form>
        </div>

        <!-- RIGHT : TABLE -->
        <div class="col-md-9">
            <table id="ampasTable"
                   class="table table-bordered table-striped text-dark"
                   width="100%">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Timestamp</th>
                        <th>Material</th>
                        <th>Pol Ampas</th>
                        <th>%Air</th>
                        <th>%Zk</th>
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
            url: 'analisa_ampas_fetch.php',
            type: 'POST'
        },
        order: [[0,'desc']],
        columns: [
            { data:'id' },
            { data:'created_at' },
            { data:'material' },
            { data:'pol_ampas' },
            { data:'air' },
            { data:'zk' },
            { data:'action', orderable:false }
        ]
    });
});
</script>
