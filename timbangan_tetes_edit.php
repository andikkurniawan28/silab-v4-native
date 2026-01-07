<?php
include('header.php');

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM mollases WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: timbangan_tetes_index.php");
    exit;
}

$data = $result->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Timbangan Tetes</h4>

    <form method="POST" action="timbangan_tetes_update.php">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <div class="form-group">
            <label>Timestamp</label>
            <input type="text" id="created_at" name="created_at"
                   class="form-control"
                   value="<?= $data['created_at'] ?>" required>
        </div>

        <div class="form-group">
            <label>Bruto</label>
            <input type="number" step="0.01" id="bruto" name="bruto"
                   class="form-control"
                   value="<?= $data['bruto'] ?>" required>
        </div>

        <div class="form-group">
            <label>Netto</label>
            <input type="number" step="0.01" id="netto" name="netto"
                   class="form-control"
                   value="<?= $data['netto'] ?>" required>
        </div>

        <div class="form-group">
            <label>Tarra</label>
            <input type="number" step="0.01" id="tarra" name="tarra"
                   class="form-control"
                   value="<?= $data['tarra'] ?>" readonly required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="timbangan_tetes_index.php" class="btn btn-secondary">Batal</a>
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
