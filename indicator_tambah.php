
<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    // 'Kabag', 
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

?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Indikator</h4>

    <form method="POST" action="indicator_store.php">
        <div class="form-group">
            <label>Nama Indikator</label>
            <input type="text" name="name"
                   class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="indicator_index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include('footer.php'); ?>
