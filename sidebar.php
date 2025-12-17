<!-- DATA -->
<?php
include 'db.php';

$stations = [];
$query = "SELECT id, name FROM stations ORDER BY id ASC";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $stations[] = $row;
    }
}
?>

<!-- VIEW -->
<ul class="navbar-nav bg-gradient-light sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <img src="/silab-v4/admin_template/img/QC.png" width="50" height="50" alt="Logo QC">
        </div>
        <div class="sidebar-brand-text mx-3">SILAB</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-home"></i>
            <span>Home</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities2"
            aria-expanded="true" aria-controls="collapseUtilities2">
            <i class="fas fa-fw fa-folder-open"></i>
            <span>Hasil Analisa</span>
        </a>
        <div id="collapseUtilities2" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu :</h6>
                <?php foreach ($stations as $station): ?>
                    <a class="collapse-item" href="hasil_analisa.php?station_id=<?= $station['id']; ?>">
                        <?= htmlspecialchars($station['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBarcode"
            aria-expanded="true" aria-controls="collapseBarcode">
            <i class="fas fa-fw fa-barcode"></i>
            <span>Cetak Barcode</span>
        </a>
        <div id="collapseBarcode" class="collapse" aria-labelledby="headingBarcode"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu :</h6>

                <?php foreach ($stations as $station): ?>
                    <a class="collapse-item"
                    href="cetak_barcode.php?station_id=<?= $station['id']; ?>">
                        <?= htmlspecialchars($station['name']); ?>
                    </a>
                <?php endforeach; ?>

            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster"
            aria-expanded="true" aria-controls="collapseMaster">
            <i class="fas fa-fw fa-database"></i>
            <span>Master</span>
        </a>
        <div id="collapseMaster" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu :</h6>
                <a class="collapse-item" href="user_index.php">User</a>
            </div>
        </div>
    </li>
    <hr class="sidebar-divider">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>