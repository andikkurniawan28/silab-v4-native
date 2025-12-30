<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Penggunaan Bahan Pembantu Proses</h4>
    
    <form action="penggunaan_bpp_proses.php" method="POST">
        <?php
        // Ambil semua chemical untuk membuat input
        include('db.php');
        $chemicalQuery = $conn->query("SELECT id, name FROM chemicals ORDER BY id");
        while ($chemical = $chemicalQuery->fetch_assoc()) {
            $colName = 'p' . $chemical['id'];
            ?>
            <div class="mb-3">
                <label for="<?php echo $colName; ?>" class="form-label">
                    <?php echo htmlspecialchars($chemical['name']); ?>
                </label>
                <input type="text" 
                       class="form-control" 
                       id="<?php echo $colName; ?>" 
                       name="<?php echo $colName; ?>"
                       placeholder="Masukkan nilai untuk <?php echo $chemical['name']; ?>">
            </div>
            <?php
        }
        ?>
        
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="penggunaan_bpp_index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('footer.php'); ?>