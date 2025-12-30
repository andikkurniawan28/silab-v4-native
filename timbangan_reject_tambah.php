<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Timbangan Reject per Charge</h4>

    <form method="POST" action="timbangan_reject_store.php">
        <div class="form-group">
            <label>Bruto</label>
            <input type="number" step="0.01" id="bruto" name="bruto"
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label>Netto</label>
            <input type="number" step="0.01" id="netto" name="netto"
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label>Tarra</label>
            <input type="number" step="0.01" id="tarra" name="tarra"
                   class="form-control" readonly required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="timbangan_reject_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    const brutoInput = document.getElementById('bruto');
    const nettoInput = document.getElementById('netto');
    const tarraInput = document.getElementById('tarra');

    function hitungTarra() {
        const bruto = parseFloat(brutoInput.value) || 0;
        const netto = parseFloat(nettoInput.value) || 0;

        if (bruto >= netto) {
            tarraInput.value = (bruto - netto).toFixed(2);
        } else {
            tarraInput.value = '';
        }
    }

    brutoInput.addEventListener('input', hitungTarra);
    nettoInput.addEventListener('input', hitungTarra);
</script>

<?php include('footer.php'); ?>
