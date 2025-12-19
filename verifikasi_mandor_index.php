<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Verifikasi Mandor</h4>

    <form method="POST" action="verifikasi_mandor_proses.php" id="verifikasiForm">

        <div class="mb-2">
            <button type="submit" class="btn btn-primary btn-sm">
                Setujui
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="checkAll">
                Pilih Semua
            </button>
        </div>

        <div class="table-responsive">
            <table id="verifikasiTable"
                   class="table table-bordered table-striped text-dark"
                   width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Material</th>
                        <th>Hasil</th>
                        <th width="30">✔</th>
                    </tr>
                </thead>
            </table>
        </div>

    </form>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {

    let table = $('#verifikasiTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        ajax: {
            url: 'verifikasi_mandor_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'material' },
            { data: 'hasil_analisa', orderable:false, searchable:false },
            { data: 'check', orderable:false, searchable:false },
        ]
    });

    $('#checkAll').on('click', function(){
        let checked = $('.row-check:checked').length !== $('.row-check').length;
        $('.row-check').prop('checked', checked);
    });

});
</script>
