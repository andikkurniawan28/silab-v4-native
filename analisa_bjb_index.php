<?php
include('header.php');

$faktor_berat = 0.1;
$faktor_ayakan = [
    1 => 1.8,
    2 => 1.44,
    3 => 1.02,
    4 => 0.73,
    5 => 0.45,
    6 => 0.25
];
?>

<div class="container-fluid">
    <h4 class="mb-3">Analisa BJB</h4>

    <div class="row">

        <!-- ================= LEFT : KALKULATOR ================= -->
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>Kalkulator BJB</strong>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered" cellpadding="8">
                            <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Ayakan + SHS</th>
                                    <th>Ayakan</th>
                                    <th>SHS</th>
                                    <th>FB</th>
                                    <th>SHS Faktor</th>
                                    <th>FA</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($i=1;$i<=6;$i++): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><input type="number" step="any" id="berat_ayakan_plus_shs<?= $i ?>" onchange="hitungBJB()"></td>
                                    <td><input type="number" step="any" id="berat_ayakan<?= $i ?>" onchange="hitungBJB()"></td>
                                    <td id="berat_shs<?= $i ?>"></td>
                                    <td id="faktor_berat<?= $i ?>"><?= $faktor_berat ?></td>
                                    <td id="berat_shs_koreksi<?= $i ?>"></td>
                                    <td id="faktor_ayakan<?= $i ?>"><?= number_format($faktor_ayakan[$i],2) ?></td>
                                    <td id="jumlah<?= $i ?>"></td>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>

                    <form action="analisa_bjb_proses.php" method="POST">
                        <div class="form-group">
                            <label>Barcode</label>
                            <input type="number" name="sample_id" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>BJB</label>
                            <input type="number" step="any" id="value" name="value" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label>CV</label>
                            <input type="number" step="any" id="cv" name="cv" class="form-control" readonly>
                        </div>

                        <button class="btn btn-primary">
                            Simpan
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <!-- ================= RIGHT : TABLE ================= -->
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>Data Analisa BJB</strong>
                </div>
                <div class="card-body">
                    <table id="bjbTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Tanggal</th>
                                <th>Material</th>
                                <th>BJB</th>
                                <th>CV</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function(){
    $('#bjbTable').DataTable({
        processing:true,
        serverSide:true,
        order:[[0,'desc']],
        ajax:{
            url:'analisa_bjb_fetch.php',
            type:'POST'
        },
        columns:[
            {data:'id'},
            {data:'created_at'},
            {data:'material'},
            {data:'bjb'},
            {data:'cv'},
            {data:'action', orderable:false}
        ]
    });
});
</script>

<script>
<?= file_get_contents('bjb_calculator.js'); ?>
</script>
