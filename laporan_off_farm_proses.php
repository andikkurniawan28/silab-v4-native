<?php
require_once('db.php');
// require_once('db_packer2.php');

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
    /** STEP 1: ambil material */
    $materials = [];
    $materialIds = [];
    $res = $conn->query("
        SELECT id, name
        FROM materials
        WHERE station_id != 12
    ");
    while ($row = $res->fetch_assoc()) {
        $materials[$row['id']] = [
            'material' => $row['name'],
            'jumlah'   => 0,
            'volume'   => 0
        ];
        $materialIds[] = $row['id'];
    }
    if (!$materialIds) return [];
    $materialIdList = implode(',', $materialIds);
    /** STEP 2: ambil methods + indicator */
    $methods = [];
    $res = $conn->query("
        SELECT m.material_id, i.name AS indicator_name
        FROM methods m
        JOIN indicators i ON i.id = m.indicator_id
        WHERE m.material_id IN ($materialIdList)
    ");
    while ($row = $res->fetch_assoc()) {
        $methods[$row['material_id']][] = $row['indicator_name'];
    }
    /** STEP 3: ambil analisa */
    $analisa = [];
    $stmt = $conn->prepare("
        SELECT *
        FROM analisa_off_farm_new
        WHERE created_at >= ?
          AND created_at < ?
          AND is_verified = 1
          AND material_id IN ($materialIdList)
    ");
    $stmt->bind_param("ss", $time['current'], $time['tomorrow']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $analisa[] = $row;
        $materials[$row['material_id']]['jumlah']++;
        $materials[$row['material_id']]['volume'] += (float)($row['volume'] ?? 0);
    }
    /** STEP 4: hitung rata-rata */
    foreach ($materials as $materialId => &$materialData) {

        if (!isset($methods[$materialId])) continue;

        foreach ($methods[$materialId] as $indicatorName) {

            $column = str_replace(' ', '_', $indicatorName);
            $sum = 0;
            $count = 0;

            foreach ($analisa as $row) {
                if (
                    $row['material_id'] == $materialId &&
                    isset($row[$column]) &&
                    $row[$column] != 0
                ) {
                    $sum += $row[$column];
                    $count++;
                }
            }

            $materialData[$indicatorName] = $count > 0
                ? round($sum / $count, 2)
                : null;
        }
    }
    return $materials;
}

function getIndicators($conn)
{
    $ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 21, 22, 23, 24, 25, 26, 34, 36];
    $sql = "
        SELECT id, name
        FROM indicators
        WHERE id IN (" . implode(',', $ids) . ")
    ";
    $res = $conn->query($sql);
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function serveKelilingProses($conn, $date, $shift)
{
    $time = determineTimeRange($date, $shift);

    /** STEP 1: ambil kspots */
    $kspots = [];
    $kspotIds = [];

    $res = $conn->query("
        SELECT id, name
        FROM kspots
        ORDER BY id
    ");

    while ($row = $res->fetch_assoc()) {
        $kspots[$row['id']] = [
            'id'    => $row['id'],
            'name'  => $row['name'],
            'sum'   => 0,
            'count' => 0
        ];
        $kspotIds[] = $row['id'];
    }

    if (!$kspotIds) {
        return [];
    }

    /** STEP 2: ambil data keliling_proses */
    $stmt = $conn->prepare("
        SELECT *
        FROM keliling_proses
        WHERE created_at >= ?
          AND created_at < ?
    ");
    $stmt->bind_param("ss", $time['current'], $time['tomorrow']);
    $stmt->execute();
    $result = $stmt->get_result();

    /** STEP 3: akumulasi p{id} */
    while ($row = $result->fetch_assoc()) {
        foreach ($kspotIds as $id) {
            $col = 'p' . $id;

            if (isset($row[$col]) && $row[$col] != 0) {
                $kspots[$id]['sum']   += (float)$row[$col];
                $kspots[$id]['count']++;
            }
        }
    }

    /** STEP 4: hitung rata-rata & rapikan output */
    $output = [];
    foreach ($kspots as $kspot) {
        $output[] = [
            'id'    => $kspot['id'],
            'name'  => $kspot['name'],
            'value' => $kspot['count'] > 0
                ? round($kspot['sum'] / $kspot['count'], 2)
                : null
        ];
    }

    return $output;
}

function servePenggunaanBPP($conn, $date, $shift)
{
    $time = determineTimeRange($date, $shift);

    /** STEP 1: ambil chemicals */
    $chemicals = [];
    $kspotIds = [];

    $res = $conn->query("
        SELECT id, name
        FROM chemicals
        ORDER BY id
    ");

    while ($row = $res->fetch_assoc()) {
        $chemicals[$row['id']] = [
            'id'    => $row['id'],
            'name'  => $row['name'],
            'sum'   => 0,
            'count' => 0
        ];
        $kspotIds[] = $row['id'];
    }

    if (!$kspotIds) {
        return [];
    }

    /** STEP 2: ambil data penggunaan_bpp */
    $stmt = $conn->prepare("
        SELECT *
        FROM penggunaan_bpp
        WHERE created_at >= ?
          AND created_at < ?
    ");
    $stmt->bind_param("ss", $time['current'], $time['tomorrow']);
    $stmt->execute();
    $result = $stmt->get_result();

    /** STEP 3: akumulasi p{id} */
    while ($row = $result->fetch_assoc()) {
        foreach ($kspotIds as $id) {
            $col = 'p' . $id;

            if (isset($row[$col]) && $row[$col] != 0) {
                $chemicals[$id]['sum']   += (float)$row[$col];
                $chemicals[$id]['count']++;
            }
        }
    }

    /** STEP 4: hitung rata-rata & rapikan output */
    $output = [];
    foreach ($chemicals as $kspot) {
        $output[] = [
            'id'    => $kspot['id'],
            'name'  => $kspot['name'],
            'value' => $kspot['count'] > 0
                ? round($kspot['sum'] / $kspot['count'], 2)
                : null
        ];
    }

    return $output;
}

function getTimbanganTetes($conn, $time)
{
    $stmt = $conn->prepare("
        SELECT SUM(netto) AS total_tetes
        FROM mollases
        WHERE created_at >= ?
          AND created_at < ?
    ");
    $stmt->bind_param("ss", $time['current'], $time['tomorrow']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return (float) ($result['total_tetes'] ?? 0);
}

function getRsIn($conn, $time)
{
    $stmt = $conn->prepare("
        SELECT SUM(value) AS total
        FROM weighing_test
        WHERE created_at >= ?
          AND created_at < ?
    ");
    $stmt->bind_param("ss", $time['current'], $time['tomorrow']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return (float) ($result['total'] ?? 0);
}

function getRs($conn, $time, $table)
{
    $stmt = $conn->prepare("
        SELECT SUM(netto) AS total
        FROM $table
        WHERE created_at >= ?
          AND created_at < ?
    ");
    $stmt->bind_param("ss", $time['current'], $time['tomorrow']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return (float) ($result['total'] ?? 0);
}

function getBalance($conn, $time, $column, $table)
{
    $stmt = $conn->prepare("
        SELECT SUM($column) AS total
        FROM $table
        WHERE created_at >= ?
          AND created_at < ?
    ");
    $stmt->bind_param("ss", $time['current'], $time['tomorrow']);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    return (float) ($result['total'] ?? 0);
}

$time = determineTimeRange($_POST['date'], $_POST['shift']);
$dataRows   = serve($conn, $_POST['date'], $_POST['shift']);
$indicators = getIndicators($conn);
$timbangan_tetes = getTimbanganTetes($conn, $time);

$rs_in = 0;
$rs_out = 0;
$reject = 0;
$conveyor_utara = 0;
$conveyor_selatan = 0;

// $rs_in = getRsIn($conn2, $time);
// $rs_out = getRs($conn2, $time, 'pringkilan');
// $reject = getRs($conn2, $time, 'rs_out');
// $conveyor_utara = getRs($conn2, $time, 'conv_utara');
// $conveyor_selatan = getRs($conn2, $time, 'conv_selatan');

$tebu_tergiling = getBalance($conn, $time, 'tebu', 'balances');
$nira_mentah_pemurnian = getBalance($conn, $time, 'flow_nm', 'balances');
$nira_mentah_gilingan = getBalance($conn, $time, 'flow_nm_gilingan', 'balances');
$imbibisi = getBalance($conn, $time, 'flow_imb', 'imbibitions');
$d1 = getBalance($conn, $time, 'flow_decanter_1st', 'balances');
$d2 = getBalance($conn, $time, 'flow_decanter_2nd', 'balances');

$keliling_proses = serveKelilingProses($conn, $_POST['date'], $_POST['shift']);
$penggunaan_bpp = servePenggunaanBPP($conn, $_POST['date'], $_POST['shift']);

if($_POST['handling'] == 'export'){
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=Laporan_Off_Farm_' . $_POST["date"] . '.xls');
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
                Laporan Off Farm - <?= date('d/m/Y', strtotime($_POST['date'])) ?>
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
                        <h6>Analisa Rata-rata</h6>
                        <table class="table table-dark table-striped table-sm table-bordered text-xs">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Material</th>
                                    <th>Sejumlah</th>
                                    <th>Hl</th>
                                    <?php foreach ($indicators as $indicator): ?>
                                        <th><?= htmlspecialchars($indicator['name']) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (empty($dataRows)): ?>
                                    <tr>
                                        <td colspan="<?= 4 + count($indicators) ?>" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Tidak ada data
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($dataRows as $row): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td class="text-uppercase"><?= htmlspecialchars($row['material']) ?></td>
                                            <td><?= $row['jumlah'] ?></td>
                                            <td><?= number_format($row['volume'], 2, ',', '.') ?></td>

                                            <?php foreach ($indicators as $indicator): ?>
                                                <td>
                                                    <?php
                                                    $key = $indicator['name'];
                                                    if (isset($row[$key]) && $row[$key] !== null) {
                                                        echo number_format($row[$key], 2, ',', '.');
                                                    }
                                                    ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="table-responsive">
                            <h6>Timbangan In Proses</h6>
                            <table class="table table-dark table-striped table-sm table-bordered text-xs">
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Senilai</th>
                                    <th>Satuan</th>
                                </tr>
                                <tr>
                                    <td>TETES</td>
                                    <td><?= number_format($timbangan_tetes) ?></td>
                                    <td>Kg</td>
                                </tr>
                                <tr>
                                    <td>RS IN</td>
                                    <td><?= number_format($rs_in) ?></td>
                                    <td>Kg</td>
                                </tr>
                                <tr>
                                    <td>RS OUT</td>
                                    <td><?= number_format($rs_out) ?></td>
                                    <td>Kg</td>
                                </tr>
                                <tr>
                                    <td>REJECT</td>
                                    <td><?= number_format($reject) ?></td>
                                    <td>Kg</td>
                                </tr>
                                <tr>
                                    <td>CONVEYOR UTARA</td>
                                    <td><?= number_format($conveyor_utara) ?></td>
                                    <td>Kg</td>
                                </tr>
                                <tr>
                                    <td>CONVEYOR SELATAN</td>
                                    <td><?= number_format($conveyor_selatan) ?></td>
                                    <td>Kg</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="table-responsive">
                            <h6>Tebu Tergiling & Flow Dalam Proses</h6>
                            <table class="table table-dark table-striped table-sm table-bordered text-xs">
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Senilai</th>
                                    <th>Satuan</th>
                                </tr>
                                <tr>
                                    <td>Tebu Tergiling</td>
                                    <td><?= number_format($tebu_tergiling, 2) ?></td>
                                    <td>Kuintal</td>
                                </tr>
                                <tr>
                                    <td>Nira Mentah dari Flow Pemurnian</td>
                                    <td><?= number_format($nira_mentah_pemurnian) ?></td>
                                    <td>m<sup>3</sup></td>
                                </tr>
                                <tr>
                                    <td>Nira Mentah dari Flow Gilingan</td>
                                    <td><?= number_format($nira_mentah_gilingan) ?></td>
                                    <td>m<sup>3</sup></td>
                                </tr>
                                <tr>
                                    <td>Air Imbibisi</td>
                                    <td><?= number_format($imbibisi) ?></td>
                                    <td>m<sup>3</sup></td>
                                </tr>
                                <tr>
                                    <td>Decanter 1st</td>
                                    <td><?= number_format($d1) ?></td>
                                    <td>m<sup>3</sup></td>
                                </tr>
                                <tr>
                                    <td>Decanter 2nd</td>
                                    <td><?= number_format($d2) ?></td>
                                    <td>m<sup>3</sup></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="table-responsive">
                            <h6>Keliling Proses</h6>
                            <table class="table table-dark table-striped table-sm table-bordered text-xs">
                                <thead>
                                    <tr>
                                        <th style="width:5%">No</th>
                                        <th>Titik</th>
                                        <th style="width:25%">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($keliling_proses)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">
                                                Tidak ada data
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($keliling_proses as $kspot): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($kspot['name']) ?></td>
                                                <td class="text-end">
                                                    <?= $kspot['value'] !== null
                                                        ? number_format($kspot['value'], 2, ',', '.')
                                                        : '<span class="text-muted">-</span>' ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="table-responsive">
                            <h6>Penggunaan BPP</h6>
                            <table class="table table-dark table-striped table-sm table-bordered text-xs">
                                <thead>
                                    <tr>
                                        <th style="width:5%">No</th>
                                        <th>Titik</th>
                                        <th style="width:25%">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($penggunaan_bpp)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">
                                                Tidak ada data
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($penggunaan_bpp as $chemical): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($chemical['name']) ?></td>
                                                <td class="text-end">
                                                    <?= $chemical['value'] !== null
                                                        ? number_format($chemical['value'], 2, ',', '.')
                                                        : '<span class="text-muted">-</span>' ?>
                                                </td>
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

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>