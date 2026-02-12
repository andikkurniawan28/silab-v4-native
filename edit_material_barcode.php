<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    'Kabag', 
    'Kasie', 
    'Kasubsie', 
    'Admin QC', 
    'Koordinator QC', 
    'Mandor Off Farm', 
    // 'Analis Off Farm', 
    // 'Mandor On Farm', 
    // 'Analis On Farm', 
    // 'Operator Pabrikasi',
    // 'Staff Teknik',
    // 'Staff Tanaman',
    // 'Staff TUK',
    // 'Direksi',
    // 'Tamu',
    ]);
include('header_rev.php'); 

if (!isset($_GET['id'])) {
    die('ID tidak valid');
}

$id = intval($_GET['id']);

$data = $conn->query("
    SELECT * FROM analisa_off_farm_new
    WHERE id='$id'
")->fetch_assoc();

$materials = $conn->query("
    SELECT id, name FROM materials ORDER BY name
");
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Material Barcode #<?= $data['id']; ?></h4>

    <form method="POST" action="edit_material_barcode_proses.php">
        <input type="hidden" name="id" value="<?= $data['id']; ?>">

        <div class="form-group">
            <label>Material</label>
            <select name="material_id" class="form-control" required>
                <?php while($m=$materials->fetch_assoc()): ?>
                    <option value="<?= $m['id']; ?>"
                        <?= $m['id']==$data['material_id']?'selected':'' ?>>
                        <?= htmlspecialchars($m['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="barcode_index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('footer.php'); ?>
