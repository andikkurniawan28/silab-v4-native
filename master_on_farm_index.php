<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Master On Farm</h4>

    <div class="table-responsive">
    <table id="master_on_farmTable" class="table table-bordered table-striped text-dark" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom TA</th>
                <th>Antrian</th>
                <th>Register</th>
                <th>Petani</th>
                <th>Nopol</th>
                <th>R ARI</th>
                <th>MBS</th>
                <th>Bobot</th>
            </tr>
        </thead>
    </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#master_on_farmTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0,'desc']],
        ajax: {
            url: 'master_on_farm_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'spta' },
            { data: 'nomor_antrian' },
            { data: 'register' },
            { data: 'petani' },
            { data: 'nopol' },
            { data: 'rendemen_ari' },
            { data: 'mutu_tebu' },
            { data: 'bobot_tebu' },
        ]
    });
});
</script>
