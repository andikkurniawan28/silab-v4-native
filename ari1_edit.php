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
    FROM analisa_on_farms
    WHERE id = '$id'
    LIMIT 1
");

if ($q->num_rows == 0) {
    die('Data analisa tidak ditemukan');
}

$analisa = $q->fetch_assoc();

?>

<div class="container-fluid">
    <h4 class="mb-3">Edit ARI 1</h4>

    <form method="POST" action="ari1_update.php">

        <div class="form-group">
            <label>ID</label>
            <input type="text"
                   name="id"
                   class="form-control"
                   value="<?= htmlspecialchars($analisa['id']); ?>"
                   readonly>
        </div>

        <div class="form-group">
            <label>Brix</label>
            <input type="number" step="any" 
                   name="brix_core"
                   id="brix_core"
                   class="form-control"
                   value="<?= $analisa['brix_core']; ?>"
                   onchange="hitungRendemen()"
                   required>
        </div>

        <div class="form-group">
            <label>Pol</label>
            <input type="number" step="any" 
                   name="pol_core"
                   id="pol_core"
                   class="form-control"
                   value="<?= $analisa['pol_core']; ?>"
                   onchange="hitungRendemen()"
                   required>
        </div>

        <div class="form-group">
            <label>Z</label>
            <input type="number" step="any" 
                   name="pol_baca_core"
                   id="pol_baca_core"
                   class="form-control"
                   value="<?= $analisa['pol_baca_core']; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>R</label>
            <input type="number" step="any" 
                   name="rendemen_core"
                   id="rendemen_core"
                   class="form-control"
                   value="<?= $analisa['rendemen_core']; ?>"
                   onchange="hitungPol()"
                   required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="ari1_index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('footer.php'); ?>

<script>
    function hitungRendemen()
    {
        brix = document.getElementById("brix_core").value;
        pol = document.getElementById("pol_core").value;
        brix = parseFloat(brix);
        pol = parseFloat(pol);
        rendemen = (pol-(0.5*(brix-pol)))*0.7;
        document.getElementById("rendemen_core").value = rendemen.toFixed(2);
    }

    function hitungPol()
    {
        brix = document.getElementById("brix_core").value;
        rendemen = document.getElementById("rendemen_core").value;
        brix = parseFloat(brix);
        rendemen = parseFloat(rendemen);
        pol = ((rendemen / 0.7) + (0.5 * brix)) / (1 + 0.5);
        document.getElementById("pol_core").value = pol.toFixed(2);
    }
</script>
