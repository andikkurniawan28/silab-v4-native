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
    'Analis Off Farm', 
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

<?php
$station_id = $_GET['station_id'] ?? null;

if (!$station_id) {
    echo '<div class="alert alert-danger">Station tidak valid.</div>';
    include('footer.php');
    exit;
}

/**
 * Station
 */
$station = $conn->query("
    SELECT name FROM stations WHERE id = $station_id
")->fetch_assoc();

/**
 * Materials (SEMUA)
 */
$materialsQ = $conn->query("
    SELECT id, name
    FROM materials
    WHERE station_id = $station_id
    ORDER BY id ASC
");
?>

<div class="container-fluid">

    <h4 class="mb-4">Hasil Analisa <?= htmlspecialchars($station['name']); ?></h4>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text"
                    id="materialSearch"
                    class="form-control"
                    placeholder="Cari material...">
            </div>
        </div>
    </div>

    <div class="row">

        <?php while ($material = $materialsQ->fetch_assoc()): ?>

            <?php
            $material_id = $material['id'];
            $showPanVolume = in_array($material_id, [43,44,45,46,47,48,49]);

            /**
             * Indikator material
             */
            $indQ = $conn->query("
                SELECT i.id, i.name
                FROM methods m
                JOIN indicators i ON i.id = m.indicator_id
                WHERE m.material_id = $material_id
                ORDER BY i.id ASC
            ");

            $indicators = [];
            while ($i = $indQ->fetch_assoc()) {
                $indicators[] = $i;
            }

            /**
             * Analisa (limit 5, verified)
             */
            $anaQ = $conn->query("
                SELECT *
                FROM analisa_off_farm_new
                WHERE material_id = $material_id
                  AND is_verified = 1
                ORDER BY created_at DESC
                LIMIT 5
            ");

            $analisa = [];
            while ($a = $anaQ->fetch_assoc()) {
                $analisa[] = $a;
            }
            ?>

            <!-- CARD -->
            <div class="col-md-<?php if(count($indicators) >= 8) echo "12"; else echo "6"; ?> mb-4 material-card"
                data-name="<?= strtolower($material['name']) ?>">
                <div class="card shadow h-100">

                    <div class="card-header bg-dark text-white font-weight-bold">
                        <a class="text-white font-weight-bold" href="hasil_analisa_per_material.php?id=<?= $material['id'] ?>">
                        <?= strtoupper(htmlspecialchars($material['name'])); ?>
                        </a>
                    </div>

                    <div class="card-body p-0">

                        <?php if (count($analisa) > 0): ?>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0 text-center text-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Time</th>
                                            <th>ID</th>
                                            <?php if ($showPanVolume): ?>
                                                <th>Pan</th>
                                                <th>Hl</th>
                                            <?php endif; ?>
                                            <?php foreach ($indicators as $ind): ?>
                                                <th><?= htmlspecialchars($ind['name']); ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($analisa as $row): ?>
                                            <tr>
                                                <td>
                                                    <?= date('d-m-Y H:i', strtotime($row['created_at'])); ?>
                                                </td>
                                                <td><?= $row['id'] ?></td>
                                                <?php if ($showPanVolume): ?>
                                                    <td><?= $row['pan'] ?></td>
                                                    <td><?= $row['volume'] ?></td>
                                                <?php endif; ?>
                                                <?php foreach ($indicators as $ind): ?>
                                                    <?php
                                                        $key = str_replace(' ', '_', $ind['name']);
                                                        $value = $row[$key];
                                                    ?>
                                                    <td>
                                                        <?= ($value === null || $value === '') ? '' : $value; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php else: ?>

                            <div class="p-3 text-center text-muted">
                                Belum ada data analisa
                            </div>

                        <?php endif; ?>

                    </div>

                </div>
            </div>

        <?php endwhile; ?>

    </div>
</div>

<script>
document.getElementById('materialSearch').addEventListener('keyup', function () {
    let keyword = this.value.toLowerCase();
    let cards = document.querySelectorAll('.material-card');

    cards.forEach(function (card) {
        let name = card.getAttribute('data-name');

        if (name.includes(keyword)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

<?php include('footer.php'); ?>
