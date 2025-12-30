
<?php include('header.php'); ?>
<?php

if (!isset($_GET['id'])) {
    header("Location: penggunaan_bpp_index.php");
    exit;
}

$id = $_GET['id'];
$query = $conn->prepare("SELECT * FROM penggunaan_bpp WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Error',
        'message' => 'Data tidak ditemukan'
    ];
    header("Location: penggunaan_bpp_index.php");
    exit;
}
?>


<div class="container-fluid">
    <h4 class="mb-3">Edit Penggunaan Bahan Pembantu Proses</h4>
    
    <form action="penggunaan_bpp_proses.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        
        <?php
        // Ambil semua chemical untuk membuat input
        $chemicalQuery = $conn->query("SELECT id, name FROM chemicals ORDER BY id");
        while ($chemical = $chemicalQuery->fetch_assoc()) {
            $colName = 'p' . $chemical['id'];
            $value = $data[$colName] ?? '';
            ?>
            <div class="mb-3">
                <label for="<?php echo $colName; ?>" class="form-label">
                    p<?php echo $chemical['id']; ?> - <?php echo htmlspecialchars($chemical['name']); ?>
                </label>
                <input type="text" 
                       class="form-control" 
                       id="<?php echo $colName; ?>" 
                       name="<?php echo $colName; ?>"
                       value="<?php echo htmlspecialchars($value); ?>"
                       placeholder="Masukkan nilai untuk <?php echo $chemical['name']; ?>">
            </div>
            <?php
        }
        ?>
        
        <div class="mb-3">
            <label class="form-label">Created At</label>
            <input type="text" 
                   class="form-control" 
                   value="<?php echo date('d-m-Y H:i:s', strtotime($data['created_at'])); ?>" 
                   disabled>
        </div>
        
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="penggunaan_bpp_index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('footer.php'); ?>