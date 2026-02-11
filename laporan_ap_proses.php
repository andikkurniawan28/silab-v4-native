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
        SELECT *
        FROM analisa_pendahuluans
        WHERE created_at >= ?
          AND created_at < ?
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
$dataRows = serve($conn, $_POST['date'], $_POST['shift']);

// Inisialisasi array untuk rata-rata dengan kolom baru
$avg = [
    // Berat Tebu (Atas, Tengah, Bawah)
    'berat_tebu_atas' => 0,
    'berat_tebu_tengah' => 0,
    'berat_tebu_bawah' => 0,
    
    // Berat Nira (Atas, Tengah, Bawah)
    'berat_nira_atas' => 0,
    'berat_nira_tengah' => 0,
    'berat_nira_bawah' => 0,
    
    // Analisa Atas
    'brix_atas' => 0,
    'pol_atas' => 0,
    'pol_baca_atas' => 0,
    'rendemen_atas' => 0,
    
    // Analisa Tengah
    'brix_tengah' => 0,
    'pol_tengah' => 0,
    'pol_baca_tengah' => 0,
    'rendemen_tengah' => 0,
    
    // Analisa Bawah
    'brix_bawah' => 0,
    'pol_bawah' => 0,
    'pol_baca_bawah' => 0,
    'rendemen_bawah' => 0,
    
    // Count untuk masing-masing kategori (jika ingin menghitung per kategori)
    'count_brix' => 0, // Hitung berdasarkan brix (seperti sebelumnya)
    'count_berat_tebu_atas' => 0,
    'count_berat_tebu_tengah' => 0,
    'count_berat_tebu_bawah' => 0,
    'count_berat_nira_atas' => 0,
    'count_berat_nira_tengah' => 0,
    'count_berat_nira_bawah' => 0
];

// Hitung total untuk rata-rata (hanya data yang tidak NULL)
foreach ($dataRows as $row) {
    // Gunakan brix_atas sebagai indikator data lengkap
    if ($row['brix_atas'] !== null) {
        // Berat Tebu (Atas, Tengah, Bawah)
        if (isset($row['berat_tebu_atas']) && $row['berat_tebu_atas'] !== null) {
            $avg['berat_tebu_atas'] += $row['berat_tebu_atas'];
            $avg['count_berat_tebu_atas']++;
        }
        if (isset($row['berat_tebu_tengah']) && $row['berat_tebu_tengah'] !== null) {
            $avg['berat_tebu_tengah'] += $row['berat_tebu_tengah'];
            $avg['count_berat_tebu_tengah']++;
        }
        if (isset($row['berat_tebu_bawah']) && $row['berat_tebu_bawah'] !== null) {
            $avg['berat_tebu_bawah'] += $row['berat_tebu_bawah'];
            $avg['count_berat_tebu_bawah']++;
        }
        
        // Berat Nira (Atas, Tengah, Bawah)
        if (isset($row['berat_nira_atas']) && $row['berat_nira_atas'] !== null) {
            $avg['berat_nira_atas'] += $row['berat_nira_atas'];
            $avg['count_berat_nira_atas']++;
        }
        if (isset($row['berat_nira_tengah']) && $row['berat_nira_tengah'] !== null) {
            $avg['berat_nira_tengah'] += $row['berat_nira_tengah'];
            $avg['count_berat_nira_tengah']++;
        }
        if (isset($row['berat_nira_bawah']) && $row['berat_nira_bawah'] !== null) {
            $avg['berat_nira_bawah'] += $row['berat_nira_bawah'];
            $avg['count_berat_nira_bawah']++;
        }
        
        // Analisa Atas
        $avg['brix_atas'] += $row['brix_atas'];
        $avg['pol_atas'] += $row['pol_atas'] ?? 0;
        $avg['pol_baca_atas'] += $row['pol_baca_atas'] ?? 0;
        $avg['rendemen_atas'] += $row['rendemen_atas'] ?? 0;
        
        // Analisa Tengah
        $avg['brix_tengah'] += $row['brix_tengah'] ?? 0;
        $avg['pol_tengah'] += $row['pol_tengah'] ?? 0;
        $avg['pol_baca_tengah'] += $row['pol_baca_tengah'] ?? 0;
        $avg['rendemen_tengah'] += $row['rendemen_tengah'] ?? 0;
        
        // Analisa Bawah
        $avg['brix_bawah'] += $row['brix_bawah'] ?? 0;
        $avg['pol_bawah'] += $row['pol_bawah'] ?? 0;
        $avg['pol_baca_bawah'] += $row['pol_baca_bawah'] ?? 0;
        $avg['rendemen_bawah'] += $row['rendemen_bawah'] ?? 0;
        
        $avg['count_brix']++;
    }
}

// Hitung rata-rata jika ada data
if ($avg['count_brix'] > 0) {
    // Analisa Atas
    $avg['brix_atas'] /= $avg['count_brix'];
    $avg['pol_atas'] /= $avg['count_brix'];
    $avg['pol_baca_atas'] /= $avg['count_brix'];
    $avg['rendemen_atas'] /= $avg['count_brix'];
    
    // Analisa Tengah
    $avg['brix_tengah'] /= $avg['count_brix'];
    $avg['pol_tengah'] /= $avg['count_brix'];
    $avg['pol_baca_tengah'] /= $avg['count_brix'];
    $avg['rendemen_tengah'] /= $avg['count_brix'];
    
    // Analisa Bawah
    $avg['brix_bawah'] /= $avg['count_brix'];
    $avg['pol_bawah'] /= $avg['count_brix'];
    $avg['pol_baca_bawah'] /= $avg['count_brix'];
    $avg['rendemen_bawah'] /= $avg['count_brix'];
}

// Hitung rata-rata untuk berat (gunakan count masing-masing)
if ($avg['count_berat_tebu_atas'] > 0) {
    $avg['berat_tebu_atas'] /= $avg['count_berat_tebu_atas'];
}
if ($avg['count_berat_tebu_tengah'] > 0) {
    $avg['berat_tebu_tengah'] /= $avg['count_berat_tebu_tengah'];
}
if ($avg['count_berat_tebu_bawah'] > 0) {
    $avg['berat_tebu_bawah'] /= $avg['count_berat_tebu_bawah'];
}
if ($avg['count_berat_nira_atas'] > 0) {
    $avg['berat_nira_atas'] /= $avg['count_berat_nira_atas'];
}
if ($avg['count_berat_nira_tengah'] > 0) {
    $avg['berat_nira_tengah'] /= $avg['count_berat_nira_tengah'];
}
if ($avg['count_berat_nira_bawah'] > 0) {
    $avg['berat_nira_bawah'] /= $avg['count_berat_nira_bawah'];
}

if ($_POST['handling'] == 'export') {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=Laporan_Analisa_Pendahuluan_' . $_POST["date"] . '.xls');
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
            font-size: 0.8rem;
        }

        .text-xs {
            font-size: .75rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        th {
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <div class="container-fluid py-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-bar-chart"></i>
                Laporan Analisa Pendahuluan - <?= date('d/m/Y', strtotime($_POST['date'])) ?>
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
                        <h6>Analisa Pendahuluan</h6>
                        <table class="table table-dark table-striped table-sm table-bordered text-xs">
                            <thead>
                                <tr>
                                    <th rowspan="2">ID</th>
                                    <th rowspan="2">Kode</th>
                                    <th rowspan="2">Wilayah</th>
                                    <th colspan="3">Berat Tebu</th>
                                    <th colspan="3">Berat Nira</th>
                                    <th colspan="3">Brix</th>
                                    <th colspan="3">Pol</th>
                                    <th colspan="3">Pol Baca</th>
                                    <th colspan="3">Rendemen</th>
                                </tr>
                                <tr>
                                    
                                    <!-- Berat Tebu -->
                                    <th>Atas</th>
                                    <th>Tengah</th>
                                    <th>Bawah</th>
                                    
                                    <!-- Berat Nira -->
                                    <th>Atas</th>
                                    <th>Tengah</th>
                                    <th>Bawah</th>
                                    
                                    <!-- Brix -->
                                    <th>Atas</th>
                                    <th>Tengah</th>
                                    <th>Bawah</th>
                                    
                                    <!-- Pol -->
                                    <th>Atas</th>
                                    <th>Tengah</th>
                                    <th>Bawah</th>
                                    
                                    <!-- Pol Baca -->
                                    <th>Atas</th>
                                    <th>Tengah</th>
                                    <th>Bawah</th>
                                    
                                    <!-- Rendemen -->
                                    <th>Atas</th>
                                    <th>Tengah</th>
                                    <th>Bawah</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (empty($dataRows)): ?>
                                    <tr>
                                        <td colspan="22" class="text-center text-light py-4">
                                            <i class="bi bi-inbox"></i> Tidak ada data
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <!-- ROW AVERAGE -->
                                    <?php if ($avg['count_brix'] > 0): ?>
                                        <tr class="table-warning text-dark fw-semibold">
                                            <td colspan="3" class="text-center">
                                                RATA-RATA
                                            </td>
                                            
                                            <!-- Berat Tebu Rata-rata -->
                                            <td><?= $avg['count_berat_tebu_atas'] > 0 ? number_format($avg['berat_tebu_atas'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $avg['count_berat_tebu_tengah'] > 0 ? number_format($avg['berat_tebu_tengah'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $avg['count_berat_tebu_bawah'] > 0 ? number_format($avg['berat_tebu_bawah'], 2, ',', '.') : '-' ?></td>
                                            
                                            <!-- Berat Nira Rata-rata -->
                                            <td><?= $avg['count_berat_nira_atas'] > 0 ? number_format($avg['berat_nira_atas'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $avg['count_berat_nira_tengah'] > 0 ? number_format($avg['berat_nira_tengah'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $avg['count_berat_nira_bawah'] > 0 ? number_format($avg['berat_nira_bawah'], 2, ',', '.') : '-' ?></td>
                                            
                                            <!-- Brix Rata-rata -->
                                            <td><?= number_format($avg['brix_atas'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['brix_tengah'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['brix_bawah'], 2, ',', '.') ?></td>
                                            
                                            <!-- Pol Rata-rata -->
                                            <td><?= number_format($avg['pol_atas'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['pol_tengah'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['pol_bawah'], 2, ',', '.') ?></td>
                                            
                                            <!-- Pol Baca Rata-rata -->
                                            <td><?= number_format($avg['pol_baca_atas'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['pol_baca_tengah'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['pol_baca_bawah'], 2, ',', '.') ?></td>
                                            
                                            <!-- Rendemen Rata-rata -->
                                            <td><?= number_format($avg['rendemen_atas'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['rendemen_tengah'], 2, ',', '.') ?></td>
                                            <td><?= number_format($avg['rendemen_bawah'], 2, ',', '.') ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    
                                    <!-- DATA ROWS -->
                                    <?php foreach ($dataRows as $row): ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= htmlspecialchars($row['code'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row['wilayah'] ?? '-') ?></td>
                                            
                                            <!-- Berat Tebu -->
                                            <td><?= isset($row['berat_tebu_atas']) && $row['berat_tebu_atas'] !== null ? number_format($row['berat_tebu_atas'], 2, ',', '.') : '-' ?></td>
                                            <td><?= isset($row['berat_tebu_tengah']) && $row['berat_tebu_tengah'] !== null ? number_format($row['berat_tebu_tengah'], 2, ',', '.') : '-' ?></td>
                                            <td><?= isset($row['berat_tebu_bawah']) && $row['berat_tebu_bawah'] !== null ? number_format($row['berat_tebu_bawah'], 2, ',', '.') : '-' ?></td>
                                            
                                            <!-- Berat Nira -->
                                            <td><?= isset($row['berat_nira_atas']) && $row['berat_nira_atas'] !== null ? number_format($row['berat_nira_atas'], 2, ',', '.') : '-' ?></td>
                                            <td><?= isset($row['berat_nira_tengah']) && $row['berat_nira_tengah'] !== null ? number_format($row['berat_nira_tengah'], 2, ',', '.') : '-' ?></td>
                                            <td><?= isset($row['berat_nira_bawah']) && $row['berat_nira_bawah'] !== null ? number_format($row['berat_nira_bawah'], 2, ',', '.') : '-' ?></td>
                                            
                                            <!-- Brix -->
                                            <td><?= $row['brix_atas'] !== null ? number_format($row['brix_atas'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $row['brix_tengah'] !== null ? number_format($row['brix_tengah'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $row['brix_bawah'] !== null ? number_format($row['brix_bawah'], 2, ',', '.') : '-' ?></td>
                                            
                                            <!-- Pol -->
                                            <td><?= $row['pol_atas'] !== null ? number_format($row['pol_atas'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $row['pol_tengah'] !== null ? number_format($row['pol_tengah'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $row['pol_bawah'] !== null ? number_format($row['pol_bawah'], 2, ',', '.') : '-' ?></td>
                                            
                                            <!-- Pol Baca -->
                                            <td><?= $row['pol_baca_atas'] !== null ? number_format($row['pol_baca_atas'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $row['pol_baca_tengah'] !== null ? number_format($row['pol_baca_tengah'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $row['pol_baca_bawah'] !== null ? number_format($row['pol_baca_bawah'], 2, ',', '.') : '-' ?></td>
                                            
                                            <!-- Rendemen -->
                                            <td><?= $row['rendemen_atas'] !== null ? number_format($row['rendemen_atas'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $row['rendemen_tengah'] !== null ? number_format($row['rendemen_tengah'], 2, ',', '.') : '-' ?></td>
                                            <td><?= $row['rendemen_bawah'] !== null ? number_format($row['rendemen_bawah'], 2, ',', '.') : '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="22" class="text-center text-light" style="font-size: 0.7rem;">
                                        <em>A = Atas, T = Tengah, B = Bawah</em>
                                    </td>
                                </tr>
                            </tfoot>
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