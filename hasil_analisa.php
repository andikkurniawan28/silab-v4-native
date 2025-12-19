<?php include('header.php'); ?>

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

    <div class="row">

        <?php while ($material = $materialsQ->fetch_assoc()): ?>

            <?php
            $material_id = $material['id'];

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
            <div class="col-md-<?php if(count($indicators) >= 8) echo "12"; else echo "6"; ?> mb-4">
                <div class="card shadow h-100">

                    <div class="card-header bg-dark text-white font-weight-bold">
                        <?= htmlspecialchars($material['name']); ?>
                    </div>

                    <div class="card-body p-0">

                        <?php if (count($analisa) > 0): ?>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0 text-center text-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Waktu</th>
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
                                                <?php foreach ($indicators as $ind): ?>
                                                    <td>
                                                        <?= $row[$ind['name']] ?? '-'; ?>
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

<?php include('footer.php'); ?>
