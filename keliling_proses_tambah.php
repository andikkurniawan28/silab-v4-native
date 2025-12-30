<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Tambah Keliling Proses</h4>
    
    <form action="keliling_proses_proses.php" method="POST">
        <?php
        // Ambil semua kspot untuk membuat input
        include('db.php');
        $kspotQuery = $conn->query("SELECT id, name FROM kspots ORDER BY id");
        while ($kspot = $kspotQuery->fetch_assoc()) {
            $colName = 'p' . $kspot['id'];
            ?>
            <div class="mb-3">
                <label for="<?php echo $colName; ?>" class="form-label">
                    <?php echo htmlspecialchars($kspot['name']); ?>
                </label>
                <input type="text" 
                       class="form-control" 
                       id="<?php echo $colName; ?>" 
                       name="<?php echo $colName; ?>"
                       placeholder="Masukkan nilai untuk <?php echo $kspot['name']; ?>">
            </div>
            <?php
        }
        ?>
        
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="keliling_proses_index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('footer.php'); ?>