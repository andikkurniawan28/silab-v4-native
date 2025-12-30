<?php
include('header2.php');
include('db_packer.php');

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM weighing_test WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: timbangan_rs_in_index.php");
    exit;
}

$data = $result->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Timbangan RS IN</h4>

    <form method="POST" action="timbangan_rs_in_update.php">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <div class="form-group">
            <label>Value</label>
            <input type="number" step="0.01" id="value" name="value"
                   class="form-control"
                   value="<?= $data['value'] ?>" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="timbangan_rs_in_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
