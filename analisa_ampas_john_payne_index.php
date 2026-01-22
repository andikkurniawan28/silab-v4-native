<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Analisa Ampas – Metode John Payne</h4>

    <div class="row">

        <!-- LEFT : FORM -->
        <div class="col-md-3">
            <form method="POST" action="analisa_ampas_john_payne_proses.php" id="johnPayneForm">

                <div class="form-group">
                    <label>Barcode</label>
                    <input type="number" name="sample_id" id="sample_id" class="form-control" autofocus required>
                </div>

                <div class="form-group">
                    <label>Berat Sampel</label>
                    <input type="number" step="any" name="berat_sampel" id="berat_sampel" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Berat Air</label>
                    <input type="number" step="any" name="berat_air" id="berat_air" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Brix</label>
                    <input type="number" step="any" name="brix" id="brix" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Pol Baca</label>
                    <input type="text" id="pol_baca" class="form-control bg-light" readonly value="0.00">
                </div>

                <div class="form-group">
                    <label>Berat Kering</label>
                    <input type="text" id="berat_kering" name="berat_kering" class="form-control bg-light" readonly value="0.00">
                </div>

                <div class="form-group">
                    <label>BJ</label>
                    <input type="text" id="bj" class="form-control bg-light" readonly value="0.000000">
                </div>

                <div class="form-group">
                    <label>Kadar Air (%)</label>
                    <input type="text" id="kadar_air" class="form-control bg-light" readonly value="0.00">
                </div>

                <div class="form-group">
                    <label>Berat Residual Juice</label>
                    <input type="text" id="berat_residual_juice" name="berat_residual_juice" class="form-control bg-light" readonly value="0.00">
                </div>

                <div class="form-group">
                    <label>% Pol</label>
                    <input type="text" id="pol_persen" name="pol_persen" class="form-control bg-light" readonly value="0.00">
                </div>

                <div class="form-group">
                    <label>Pol Ampas</label>
                    <input type="text" id="pol_ampas" class="form-control bg-light" name="pol_ampas" readonly value="0.00">
                </div>

                <button class="btn btn-primary btn-block">Simpan</button>
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
        processing:true,
        serverSide:true,
        ajax:{
            url:'analisa_ampas_john_payne_fetch.php',
            type:'POST'
        },
        order:[[0,'desc']],
        columns:[
            {data:'id'},
            {data:'created_at'},
            {data:'pol_ampas'},
            {data:'air'},
            {data:'zk'},
            {data:'action'},
        ]
    });
});
</script>

<script>
const bjTable = {
    0.0:0.998203,0.1:0.998588,0.2:0.998973,0.3:0.999358,0.4:0.999744,
    0.5:1.000130,0.6:1.000516,0.7:1.000902,0.8:1.001289,0.9:1.001676,
    1.0:1.002063,1.1:1.002451,1.2:1.002839,1.3:1.003227,1.4:1.003615,
    1.5:1.004004,1.6:1.004393,1.7:1.004782,1.8:1.005172,1.9:1.005562,
    2.0:1.005952
};

$('#sample_id').on('change', function(){
    let id = $(this).val();
    if(!id) return;

    fetch('analisa_ampas_john_payne_fetch.php?pol=1&id='+id)
        .then(r=>r.json())
        .then(d=>{
            $('#pol_baca').val(d.pol_baca);
            $('#kadar_air').val(d.kadar_air);
            $('#berat_kering').val((100-d.kadar_air).toFixed(2));
            updateAll();
        });
});

function updateAll(){
    let brix = parseFloat($('#brix').val())||0;
    let rounded = Math.round(brix*10)/10;
    $('#bj').val((bjTable[rounded]||0).toFixed(6));

    let sampel = parseFloat($('#berat_sampel').val())||0;
    let kering = parseFloat($('#berat_kering').val())||0;
    let residual = sampel*kering/100;
    $('#berat_residual_juice').val(residual.toFixed(2));

    let pol = parseFloat($('#pol_baca').val())||0;
    let bj = parseFloat($('#bj').val())||1;
    let polPersen = pol*0.286/bj*10;
    $('#pol_persen').val(polPersen.toFixed(2));

    let air = parseFloat($('#berat_air').val())||0;
    let ampas = sampel>0 ? ((polPersen*0.26*(air+residual))/sampel) : 0;
    $('#pol_ampas').val(ampas.toFixed(2));
}

$('#brix,#berat_sampel,#berat_air').on('input',updateAll);
</script>
