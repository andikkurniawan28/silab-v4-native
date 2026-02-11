<?php
include('header.php');

if (!isset($_GET['id'])) {
    die('ID analisa tidak ditemukan');
}

$id = $_GET['id'];

/**
 * Ambil data analisa + material
 */
$q = $conn->query("
    SELECT *
    FROM analisa_pendahuluans
    WHERE id = '$id'
    LIMIT 1
");

if ($q->num_rows == 0) {
    die('Data analisa tidak ditemukan');
}

$analisa = $q->fetch_assoc();

?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Analisa Pendahuluan</h4>

    <form method="POST" action="analisa_pendahuluan_update.php">

        <div class="form-group">
            <label>ID</label>
            <input type="text"
                   name="id"
                   class="form-control"
                   value="<?= htmlspecialchars($analisa['id']); ?>"
                   readonly>
        </div>

        <div class="form-group">
            <label>Berat Tebu Atas</label>
            <input type="number" step="any" 
                   name="berat_tebu_atas"
                   id="berat_tebu_atas"
                   class="form-control"
                   value="<?= $analisa['berat_tebu_atas']; ?>"
                   >
        </div>

        <div class="form-group">
            <label>Berat Nira Atas</label>
            <input type="number" step="any" 
                   name="berat_nira_atas"
                   id="berat_nira_atas"
                   class="form-control"
                   value="<?= $analisa['berat_nira_atas']; ?>"
                   >
        </div>

        <div class="form-group">
            <label>Brix Atas</label>
            <input type="number" step="any" 
                   name="brix_atas"
                   id="brix_atas"
                   class="form-control"
                   value="<?= $analisa['brix_atas']; ?>"
                   onchange="hitungRendemen()"
                   >
        </div>

        <div class="form-group">
            <label>Pol Atas</label>
            <input type="number" step="any" 
                   name="pol_atas"
                   id="pol_atas"
                   class="form-control"
                   value="<?= $analisa['pol_atas']; ?>"
                   onchange="hitungRendemen()"
                   >
        </div>

        <div class="form-group">
            <label>Z Atas</label>
            <input type="number" step="any" 
                   name="pol_baca_atas"
                   id="pol_baca_atas"
                   class="form-control"
                   value="<?= $analisa['pol_baca_atas']; ?>"
                   >
        </div>

        <div class="form-group">
            <label>R Atas</label>
            <input type="number" step="any" 
                   name="rendemen_atas"
                   id="rendemen_atas"
                   class="form-control"
                   value="<?= $analisa['rendemen_atas']; ?>"
                   onchange="hitungPol()"
                   >
        </div><div class="form-group">
            <label>Berat Tebu tengah</label>
            <input type="number" step="any" 
                   name="berat_tebu_tengah"
                   id="berat_tebu_tengah"
                   class="form-control"
                   value="<?= $analisa['berat_tebu_tengah']; ?>"
                   >
        </div>

        <div class="form-group">
            <label>Berat Nira tengah</label>
            <input type="number" step="any" 
                   name="berat_nira_tengah"
                   id="berat_nira_tengah"
                   class="form-control"
                   value="<?= $analisa['berat_nira_tengah']; ?>"
                   >
        </div>

        <div class="form-group">
            <label>Brix Tengah</label>
            <input type="number" step="any" 
                   name="brix_tengah"
                   id="brix_tengah"
                   class="form-control"
                   value="<?= $analisa['brix_tengah']; ?>"
                   onchange="hitungRendemen()"
                   >
        </div>

        <div class="form-group">
            <label>Pol Tengah</label>
            <input type="number" step="any" 
                   name="pol_tengah"
                   id="pol_tengah"
                   class="form-control"
                   value="<?= $analisa['pol_tengah']; ?>"
                   onchange="hitungRendemen()"
                   >
        </div>

        <div class="form-group">
            <label>Z Tengah</label>
            <input type="number" step="any" 
                   name="pol_baca_tengah"
                   id="pol_baca_tengah"
                   class="form-control"
                   value="<?= $analisa['pol_baca_tengah']; ?>"
                   >
        </div>

        <div class="form-group">
            <label>R Tengah</label>
            <input type="number" step="any" 
                   name="rendemen_tengah"
                   id="rendemen_tengah"
                   class="form-control"
                   value="<?= $analisa['rendemen_tengah']; ?>"
                   onchange="hitungPol()"
                   >
        </div>
        
        <div class="form-group">
            <label>Berat Tebu bawah</label>
            <input type="number" step="any" 
                   name="berat_tebu_bawah"
                   id="berat_tebu_bawah"
                   class="form-control"
                   value="<?= $analisa['berat_tebu_bawah']; ?>"
                   >
        </div>

        <div class="form-group">
            <label>Berat Nira bawah</label>
            <input type="number" step="any" 
                   name="berat_nira_bawah"
                   id="berat_nira_bawah"
                   class="form-control"
                   value="<?= $analisa['berat_nira_bawah']; ?>"
                   >
        </div>

        <div class="form-group">
            <label>Brix Bawah</label>
            <input type="number" step="any" 
                   name="brix_bawah"
                   id="brix_bawah"
                   class="form-control"
                   value="<?= $analisa['brix_bawah']; ?>"
                   onchange="hitungRendemen()"
                   >
        </div>

        <div class="form-group">
            <label>Pol Bawah</label>
            <input type="number" step="any" 
                   name="pol_bawah"
                   id="pol_bawah"
                   class="form-control"
                   value="<?= $analisa['pol_bawah']; ?>"
                   onchange="hitungRendemen()"
                   >
        </div>

        <div class="form-group">
            <label>Z Bawah</label>
            <input type="number" step="any" 
                   name="pol_baca_bawah"
                   id="pol_baca_bawah"
                   class="form-control"
                   value="<?= $analisa['pol_baca_bawah']; ?>"
                   >
        </div>

        <div class="form-group">
            <label>R Bawah</label>
            <input type="number" step="any" 
                   name="rendemen_bawah"
                   id="rendemen_bawah"
                   class="form-control"
                   value="<?= $analisa['rendemen_bawah']; ?>"
                   onchange="hitungPol()"
                   >
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="analisa_pendahuluan_index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('footer.php'); ?>

<script>
    function hitungRendemen()
    {
        brix = document.getElementById("brix_atas").value;
        pol = document.getElementById("pol_atas").value;
        brix = parseFloat(brix);
        pol = parseFloat(pol);
        rendemen = (pol-(0.4*(brix-pol)))*0.5;
        document.getElementById("rendemen_atas").value = rendemen.toFixed(2);
        brix = document.getElementById("brix_tengah").value;
        pol = document.getElementById("pol_tengah").value;
        brix = parseFloat(brix);
        pol = parseFloat(pol);
        rendemen = (pol-(0.4*(brix-pol)))*0.5;
        document.getElementById("rendemen_tengah").value = rendemen.toFixed(2);
        brix = document.getElementById("brix_bawah").value;
        pol = document.getElementById("pol_bawah").value;
        brix = parseFloat(brix);
        pol = parseFloat(pol);
        rendemen = (pol-(0.4*(brix-pol)))*0.5;
        document.getElementById("rendemen_bawah").value = rendemen.toFixed(2);
    }

    function hitungPol()
    {
        brix = document.getElementById("brix_atas").value;
        rendemen = document.getElementById("rendemen_atas").value;
        brix = parseFloat(brix);
        rendemen = parseFloat(rendemen);
        pol = ((rendemen / 0.5) + (0.4 * brix)) / (1 + 0.4);
        document.getElementById("pol_atas").value = pol.toFixed(2);
        brix = document.getElementById("brix_tengah").value;
        rendemen = document.getElementById("rendemen_tengah").value;
        brix = parseFloat(brix);
        rendemen = parseFloat(rendemen);
        pol = ((rendemen / 0.5) + (0.4 * brix)) / (1 + 0.4);
        document.getElementById("pol_tengah").value = pol.toFixed(2);
        brix = document.getElementById("brix_bawah").value;
        rendemen = document.getElementById("rendemen_bawah").value;
        brix = parseFloat(brix);
        rendemen = parseFloat(rendemen);
        pol = ((rendemen / 0.5) + (0.4 * brix)) / (1 + 0.4);
        document.getElementById("pol_bawah").value = pol.toFixed(2);
    }
</script>
