<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    'Kabag', 
    'Kasie', 
    'Kasubsie', 
    // 'Admin QC', 
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

$id = $_GET['id'];

$chemical = $conn->query("
    SELECT * FROM chemicals WHERE id=$id
")->fetch_assoc();
?>

<div class="container-fluid">
    <h4 class="mb-3">Edit Bahan Pembantu Proses</h4>

    <form method="POST" action="chemical_update.php">
        <input type="hidden" name="id"
               value="<?= $chemical['id']; ?>">

        <div class="form-group">
            <label>Nama Bahan Pembantu Proses</label>
            <input type="text" name="name"
                   class="form-control"
                   value="<?= $chemical['name']; ?>"
                   required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="chemical_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
