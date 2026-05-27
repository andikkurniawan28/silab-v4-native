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
    'Analis Off Farm', 
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
    <h4 class="mb-3">Analisa pH & Turbidity</h4>

    <div class="row">

        <!-- LEFT : FORM -->
        <div class="col-md-3">
            <form method="POST" action="analisa_ph_proses.php">

                <div class="form-group">
                    <label>Barcode</label>
                    <input type="number" name="sample_id" class="form-control" autofocus required>
                </div>

                <div class="form-group">
                    <label>pH</label>
                    <input type="number" step="any" name="pH" class="form-control">
                </div>

                <div class="form-group">
                    <label>Turbidity</label>
                    <input type="number" step="any" name="Turbidity" class="form-control">
                </div>

                <button class="btn btn-primary btn-block">
                    Simpan
                </button>
            </form>
        </div>

        <!-- RIGHT : TABLE -->
        <div class="col-md-9">
            <table id="phTable"
                   class="table table-bordered table-striped text-dark"
                   width="100%">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Timestamp</th>
                        <th>Material</th>
                        <th>pH</th>
                        <th>Turbidity</th>
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
    $('#phTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'analisa_ph_fetch.php',
            type: 'POST'
        },
        order: [[0,'desc']],
        columns: [
            { data:'id' },
            { data:'created_at' },
            { data:'material' },
            { data:'ph' },
            { data:'turbidity' },
            { data:'action', orderable:false }
        ]
    });
});
</script>
