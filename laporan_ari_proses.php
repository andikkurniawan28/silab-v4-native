<?php
require_once('db.php');

function determineTimeRange($date, $shift)
{
    switch ($shift) {
        case 0:
            $current = "$date 06:00";
            $tomorrow = date("Y-m-d H:i", strtotime("$current +24 hours"));
            break;
        case 1:
            $current = "$date 05:00";
            $tomorrow = date("Y-m-d H:i", strtotime("$current +8 hours"));
            break;
        case 2:
            $current = "$date 13:00";
            $tomorrow = date("Y-m-d H:i", strtotime("$current +8 hours"));
            break;
        case 3:
            $current = "$date 21:00";
            $tomorrow = date("Y-m-d H:i", strtotime("$current +8 hours"));
            break;
    }
    return compact('current', 'tomorrow');
}

function serve($conn, $date, $shift)
{
    $time = determineTimeRange($date, $shift);
    $analisa = [];
    $stmt = $conn->prepare("
        SELECT id, nomor_antrian, register, brix_ari, pol_ari, pol_baca_ari, rendemen_ari
        FROM analisa_on_farms
        WHERE ari_at >= ?
          AND ari_at < ?
    ");
    $stmt->bind_param("ss", $time['current'], $time['tomorrow']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $analisa[] = $row;
    }
    return $analisa;
}

$time = determineTimeRange($_POST['date'], $_POST['shift']);
$dataRows   = serve($conn, $_POST['date'], $_POST['shift']);

$avg = [
    'brix_ari' => 0,
    'pol_ari' => 0,
    'pol_baca_ari' => 0,
    'rendemen_ari' => 0,
    'count' => 0
];

foreach ($dataRows as $row) {
    if ($row['brix_ari'] !== null) {
        $avg['brix_ari'] += $row['brix_ari'];
        $avg['pol_ari'] += $row['pol_ari'];
        $avg['pol_baca_ari'] += $row['pol_baca_ari'];
        $avg['rendemen_ari'] += $row['rendemen_ari'];
        $avg['count']++;
    }
}

if ($avg['count'] > 0) {
    $avg['brix_ari'] /= $avg['count'];
    $avg['pol_ari'] /= $avg['count'];
    $avg['pol_baca_ari'] /= $avg['count'];
    $avg['rendemen_ari'] /= $avg['count'];
}

if ($_POST['handling'] == 'export') {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=Laporan_ARI_' . $_POST["date"] . '.xls');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Laboratorium</title>
    <link rel="icon" type="image/png" href="/silab-v4/admin_template/img/QC.png" />

    <!-- Meta wajib -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .card {
            border-radius: .75rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .text-xs {
            font-size: .75rem;
        }
    </style>
</head>

<body>

    <div class="container-fluid py-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-bar-chart"></i>
                Laporan ARI - <?= date('d/m/Y', strtotime($_POST['date'])) ?>
            </h5>

            <!-- BADGE SHIFT -->
            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                <?php
                switch ($_POST['shift']) {
                    case 0:
                        echo "Harian";
                        break;
                    case 1:
                        echo "Pagi";
                        break;
                    case 2:
                        echo "Sore";
                        break;
                    case 3:
                        echo "Malam";
                        break;
                }
                ?>
            </span>
        </div>

        <!-- CARD -->
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="col-md-12">
                    <div class="table-responsive">
                        <h6>Analisa</h6>
                        <table class="table table-dark table-striped table-sm table-bordered text-xs">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nomor Antrian</th>
                                    <th>Register</th>
                                    <th>Brix</th>
                                    <th>Pol</th>
                                    <th>Pol Baca</th>
                                    <th>Rendemen</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (empty($dataRows)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-light py-4">
                                            <i class="bi bi-inbox"></i> Tidak ada data
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php if (!empty($dataRows)): ?>
                                        <tr class="table-warning text-dark fw-semibold">
                                            <td colspan="3" class="text-center">
                                                AVERAGE
                                            </td>
                                            <td><?= number_format($avg['brix_ari'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['pol_ari'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['pol_baca_ari'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['rendemen_ari'], 2, ',', '.') ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php foreach ($dataRows as $row): ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= $row['nomor_antrian'] ?></td>
                                            <td><?= $row['register'] ?></td>
                                            <td><?= $row['brix_ari'] ?></td>
                                            <td><?= $row['pol_ari'] ?></td>
                                            <td><?= $row['pol_baca_ari'] ?></td>
                                            <td><?= $row['rendemen_ari'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>