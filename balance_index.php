<?php
include('header.php');
?>

<div class="container-fluid">
    <h4 class="mb-3">Flow</h4>

    <div class="card shadow mb-4">
        <div class="card-body">

            <form method="POST" action="balance_proses.php" class="text-dark mb-4">
            <?php $current_hour = date('H') - 1; ?>

            <!-- ROW 1 : Tanggal & Jam -->
            <div class="form-row mb-3">
                <div class="col-md-3">
                    <label>Tanggal</label>
                    <input
                        type="date"
                        name="date"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label>Jam</label>
                    <select class="form-control" name="created_at">
                        <?php for ($i = 0; $i <= 23; $i++): ?>
                            <option value="<?= $i ?>" <?= ($current_hour == $i) ? 'selected' : '' ?>>
                                <?= $i ?>:00 - <?= $i + 1 ?>:00
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <!-- ROW 2 : Tebu & Totalizer Pemurnian -->
            <div class="form-row mb-3">
                <div class="col-md-3">
                    <label>Tebu</label>
                    <input
                        type="number"
                        step="any"
                        name="tebu"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label>Totalizer Pemurnian</label>
                    <input
                        type="number"
                        step="any"
                        name="totalizer"
                        class="form-control"
                        required
                    >
                </div>
            </div>

            <!-- ROW 3 : Totalizer Gilingan & Decanter 1st -->
            <div class="form-row mb-3">
                <div class="col-md-3">
                    <label>Totalizer Gilingan</label>
                    <input
                        type="number"
                        step="any"
                        name="totalizer_gilingan"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label>Totalizer Decanter 1st</label>
                    <input
                        type="number"
                        step="any"
                        name="totalizer_decanter_1st"
                        class="form-control"
                        required
                    >
                </div>
            </div>

            <!-- ROW 4 : Decanter 2nd & SFC -->
            <div class="form-row mb-3">
                <div class="col-md-3">
                    <label>Totalizer Decanter 2nd</label>
                    <input
                        type="number"
                        step="any"
                        name="totalizer_decanter_2nd"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <label>SFC</label>
                    <input
                        type="number"
                        step="any"
                        name="sfc"
                        class="form-control"
                        required
                    >
                </div>
            </div>

            <!-- Hidden user -->
            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? 0 ?>">

            <!-- Submit -->
            <div class="form-row">
                <div class="col-md-6">
                    <button class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </div>
        </form>


            <div class="table-responsive">

            <table id="balanceTable" class="table table-bordered" width="100%">
                <thead>
                    <tr>
                        <th>ID</td>
                        <th>Timestamp</td>
                        <th>Tebu</td>
                        <th>Totalizer Pemurnian</td>
                        <th>Totalizer Gilingan</td>
                        <th>Totalizer Decanter 1<sup>st</sup></td>
                        <th>Totalizer Decanter 2<sup>nd</sup></td>
                        <th>Flow NM Pemurnian</td>
                        <th>Flow NM Gilingan</td>
                        <th>Flow Decanter 1<sup>st</sup></td>
                        <th>Flow Decanter 2<sup>nd</sup></td>
                        <th>NM % Tebu Pemurnian</td>
                        <th>NM % Tebu Gilingan</td>
                        <th>SFC</td>
                        <!-- <th>User</td> -->
                        <th>Action</td>
                    </tr>
                </thead>
            </table>
            </div>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$('#balanceTable').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:'balance_fetch.php',
        type:'POST'
    },
    order:[[0,'desc']],
    columns:[
        {data:'id'},
        {data:'created_at'},
        {data:'tebu'},
        {data:'totalizer'},
        {data:'totalizer_gilingan'},
        {data:'totalizer_d1'},
        {data:'totalizer_d2'},
        {data:'flow_nm'},
        {data:'flow_nm_gilingan'},
        {data:'flow_decanter_1st'},
        {data:'flow_decanter_2nd'},
        {data:'nm_persen_tebu'},
        {data:'nm_persen_tebu_gilingan'},
        {data:'sfc'},
        {data:'action', orderable:false}
    ]
});
</script>
