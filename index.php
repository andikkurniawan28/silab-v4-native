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
    'Mandor On Farm',
    'Analis On Farm',
    'Operator Pabrikasi',
    // 'Staff Teknik',
    // 'Staff Tanaman',
    // 'Staff TUK',
    // 'Direksi',
    // 'Tamu',
]);
include('header_rev.php');

?>

<!-- VIEW -->
<div class="container-fluid">
    <h4 class="mb-3">Home</h4>

    <?php

    // Tentukan tanggal produksi
    $now = new DateTime();

    if ($now->format('H:i:s') < '05:00:00') {
        $production_date = date('Y-m-d', strtotime('-1 day'));
    } else {
        $production_date = date('Y-m-d');
    }

    // Rentang shift berdasarkan hari produksi
    $pagi_start  = $production_date . ' 05:00:00';
    $pagi_end    = $production_date . ' 12:59:59';

    $sore_start  = $production_date . ' 13:00:00';
    $sore_end    = $production_date . ' 20:59:59';

    $malam_start = $production_date . ' 21:00:00';
    $malam_end   = date('Y-m-d 04:59:59', strtotime($production_date . ' +1 day'));


    // PAGI
    $npp_pagi = $conn->query("
        SELECT
            AVG(`%R`) AS avg_r,
            MIN(`%R`) AS min_r,
            MAX(`%R`) AS max_r,
            COUNT(*) AS total
        FROM analisa_off_farm_new
        WHERE material_id = 3
        AND is_verified = 1
        AND created_at BETWEEN '$pagi_start' AND '$pagi_end'
    ")->fetch_assoc();


    // SORE
    $npp_sore = $conn->query("
        SELECT
            AVG(`%R`) AS avg_r,
            MIN(`%R`) AS min_r,
            MAX(`%R`) AS max_r,
            COUNT(*) AS total
        FROM analisa_off_farm_new
        WHERE material_id = 3
        AND is_verified = 1
        AND created_at BETWEEN '$sore_start' AND '$sore_end'
    ")->fetch_assoc();


    // MALAM
    $npp_malam = $conn->query("
        SELECT
            AVG(`%R`) AS avg_r,
            MIN(`%R`) AS min_r,
            MAX(`%R`) AS max_r,
            COUNT(*) AS total
        FROM analisa_off_farm_new
        WHERE material_id = 3
        AND is_verified = 1
        AND created_at BETWEEN '$malam_start' AND '$malam_end'
    ")->fetch_assoc();

    ?>
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 bg-primary">
                <div class="card-body bg-primary">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                NPP Pagi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= number_format($npp_pagi['avg_r'] ?? 0, 2) ?><sub>(%R)</sub>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= $npp_pagi['total'] ?? 0 ?> Sampel
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                Min : <?= number_format($npp_pagi['min_r'] ?? 0, 2) ?>
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-white">
                                Max : <?= number_format($npp_pagi['max_r'] ?? 0, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 bg-primary">
                <div class="card-body bg-primary">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                NPP Sore
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= number_format($npp_sore['avg_r'] ?? 0, 2) ?><sub>(%R)</sub>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= $npp_sore['total'] ?? 0 ?> Sampel
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                Min : <?= number_format($npp_sore['min_r'] ?? 0, 2) ?>
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-white">
                                Max : <?= number_format($npp_sore['max_r'] ?? 0, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 bg-primary">
                <div class="card-body bg-primary">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                NPP Malam
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= number_format($npp_malam['avg_r'] ?? 0, 2) ?><sub>(%R)</sub>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= $npp_malam['total'] ?? 0 ?> Sampel
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                Min : <?= number_format($npp_malam['min_r'] ?? 0, 2) ?>
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-white">
                                Max : <?= number_format($npp_malam['max_r'] ?? 0, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    // PAGI
    $ari_pagi = $conn->query("
        SELECT
            AVG(rendemen_ari) AS avg_r,
            MIN(rendemen_ari) AS min_r,
            MAX(rendemen_ari) AS max_r,
            COUNT(*) AS total
        FROM analisa_on_farms
        WHERE ari_at BETWEEN '$pagi_start' AND '$pagi_end'
    ")->fetch_assoc();


    // SORE
    $ari_sore = $conn->query("
        SELECT
            AVG(rendemen_ari) AS avg_r,
            MIN(rendemen_ari) AS min_r,
            MAX(rendemen_ari) AS max_r,
            COUNT(*) AS total
        FROM analisa_on_farms
        WHERE ari_at BETWEEN '$sore_start' AND '$sore_end'
    ")->fetch_assoc();


    // MALAM
    $ari_malam = $conn->query("
        SELECT
            AVG(rendemen_ari) AS avg_r,
            MIN(rendemen_ari) AS min_r,
            MAX(rendemen_ari) AS max_r,
            COUNT(*) AS total
        FROM analisa_on_farms
        WHERE ari_at BETWEEN '$malam_start' AND '$malam_end'
    ")->fetch_assoc();

    ?>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 bg-danger">
                <div class="card-body bg-danger">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                ARI Pagi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= number_format($ari_pagi['avg_r'] ?? 0, 2) ?><sub>(%R)</sub>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= $ari_pagi['total'] ?? 0 ?> Sampel
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                Min : <?= number_format($ari_pagi['min_r'] ?? 0, 2) ?>
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-white">
                                Max : <?= number_format($ari_pagi['max_r'] ?? 0, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 bg-danger">
                <div class="card-body bg-danger">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                ARI Sore
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= number_format($ari_sore['avg_r'] ?? 0, 2) ?><sub>(%R)</sub>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= $ari_sore['total'] ?? 0 ?> Sampel
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                Min : <?= number_format($ari_sore['min_r'] ?? 0, 2) ?>
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-white">
                                Max : <?= number_format($ari_sore['max_r'] ?? 0, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 bg-danger">
                <div class="card-body bg-danger">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                ARI Malam
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= number_format($ari_malam['avg_r'] ?? 0, 2) ?><sub>(%R)</sub>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                <?= $ari_malam['total'] ?? 0 ?> Sampel
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">
                                Min : <?= number_format($ari_malam['min_r'] ?? 0, 2) ?>
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-white">
                                Max : <?= number_format($ari_malam['max_r'] ?? 0, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    function getJumlahPurity($conn, $table, $start, $end, $type)
    {
        if ($type == 'offfarm') {
            return $conn->query("
                    SELECT COUNT(*) AS total
                    FROM analisa_off_farm_new
                    WHERE created_at BETWEEN '$start' AND '$end'
                    AND material_id != 3
                    AND Pol IS NOT NULL
                ")->fetch_assoc();
        }

        if ($type == 'npp') {
            return $conn->query("
                    SELECT COUNT(*) AS total
                    FROM analisa_off_farm_new
                    WHERE created_at BETWEEN '$start' AND '$end'
                    AND material_id = 3
                    AND Pol IS NOT NULL
                ")->fetch_assoc();
        }

        if ($type == 'ari') {
            return $conn->query("
                    SELECT COUNT(*) AS total
                    FROM analisa_on_farms
                    WHERE ari_at BETWEEN '$start' AND '$end'
                    AND rendemen_ari IS NOT NULL
                ")->fetch_assoc();
        }
    }


    // PAGI
    $offfarm_pagi = getJumlahPurity($conn, 'analisa_off_farm_new', $pagi_start, $pagi_end, 'offfarm');
    $npp_pagi     = getJumlahPurity($conn, 'analisa_off_farm_new', $pagi_start, $pagi_end, 'npp');
    $ari_pagi     = getJumlahPurity($conn, 'analisa_on_farms', $pagi_start, $pagi_end, 'ari');

    // SIANG
    $offfarm_siang = getJumlahPurity($conn, 'analisa_off_farm_new', $sore_start, $sore_end, 'offfarm');
    $npp_siang     = getJumlahPurity($conn, 'analisa_off_farm_new', $sore_start, $sore_end, 'npp');
    $ari_siang     = getJumlahPurity($conn, 'analisa_on_farms', $sore_start, $sore_end, 'ari');

    // MALAM
    $offfarm_malam = getJumlahPurity($conn, 'analisa_off_farm_new', $malam_start, $malam_end, 'offfarm');
    $npp_malam     = getJumlahPurity($conn, 'analisa_off_farm_new', $malam_start, $malam_end, 'npp');
    $ari_malam     = getJumlahPurity($conn, 'analisa_on_farms', $malam_start, $malam_end, 'ari');

    ?>

    <div class="row">

        <?php
        $data = [
            'Pagi' => [$offfarm_pagi, $npp_pagi, $ari_pagi],
            'Sore' => [$offfarm_siang, $npp_siang, $ari_siang],
            'Malam' => [$offfarm_malam, $npp_malam, $ari_malam]
        ];

        foreach ($data as $shift => $val):
        ?>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 bg-secondary">
                    <div class="card-body text-white">

                        <div class="text-xs font-weight-bold text-uppercase mb-3">
                            Estimasi Penggunaan Form AB <?= $shift ?>
                        </div>

                        <div class="row no-gutters align-items-center">

                            <div class="col mr-6">

                                <div class="mb-2">
                                    <b>Lab Off Farm :</b>
                                    <?= $val[0]['total'] ?> Sampel * 5mL = <?= $val[0]['total'] * 5 ?> mL
                                </div>

                                <div class="mb-2">
                                    <b>Lab NPP :</b>
                                    <?= $val[1]['total'] ?> Sampel * 5mL = <?= $val[1]['total'] * 5 ?> mL
                                </div>

                                <div>
                                    <b>Lab ARI :</b>
                                    <?= $val[2]['total'] ?> Sampel * 5mL = <?= $val[2]['total'] * 5 ?> mL
                                </div>

                            </div>


                            <div class="col-auto">
                                <i class="fas fa-flask fa-2x text-white"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm text-center text-dark" id="dashboardTable">
                <thead class="table-dark">
                    <tr>
                        <th>Jam</th>
                        <th>Brix NPP</th>
                        <th>Pol NPP</th>
                        <!-- <th>HK NPP</th> -->
                        <th>%R NPP</th>
                        <th>Pol Ampas</th>
                        <th>Zat Kering</th>
                        <th>HK Tetes</th>
                        <th>IU GKP</th>
                        <th>Tebu Tergiling</th>
                        <th>Flow NM</th>
                        <th>NM%TEBU</th>
                        <th>Flow IMB</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const today = new Date().toISOString().slice(0, 10);

    // 🔥 ambil dari API
    fetch('dashboard_engine.php?date=' + today)
        .then(res => res.json())
        .then(data => renderTable(data))
        .catch(err => {
            console.error(err);
            alert('Gagal load dashboard');
        });

    function renderTable(data) {

        const map = {};

        /* =========================================
           INSERT DATA KE MAP
        ========================================= */
        function insert(arr, key) {
            (arr || []).forEach(row => {

                // pastikan format key per jam
                const hourKey = row.created_at.substring(0, 13);

                if (!map[hourKey]) {
                    map[hourKey] = {};
                }

                map[hourKey][key] = parseFloat(row.value) || 0;
            });
        }

        insert(data.brix_npp, 'brix');
        insert(data.pol_npp, 'pol');
        insert(data.hk_npp, 'hk');
        insert(data.rendemen_npp, 'rendemen');
        insert(data.hk_tetes, 'hk_tetes');
        insert(data.iu_gkp, 'iu_gkp');
        insert(data.pol_ampas, 'pol_ampas');
        insert(data.zk_ampas, 'zk_ampas');
        insert(data.tebu_tergiling, 'tebu');
        insert(data.flow_nm, 'nm');
        insert(data.flow_imb, 'imb');
        insert(data.nm_persen_tebu, 'nm_persen_tebu');

        const tbody = document.querySelector('#dashboardTable tbody');
        tbody.innerHTML = '';

        /* =========================================
           LOOP 24 JAM SHIFT
           06:00 → 05:00
        ========================================= */
        const baseDate = new Date(today + 'T06:00:00');

        for (let i = 0; i < 24; i++) {

            const current = new Date(baseDate);
            current.setHours(current.getHours() + i);

            const hourKey =
                current.getFullYear() + '-' +
                String(current.getMonth() + 1).padStart(2, '0') + '-' +
                String(current.getDate()).padStart(2, '0') + ' ' +
                String(current.getHours()).padStart(2, '0');

            const row = map[hourKey] || {};

            tbody.innerHTML += `
            <tr>
                <td>${formatJam(current)}</td>
                <td>${val(row.brix)}</td>
                <td>${val(row.pol)}</td>
                <td>${val(row.rendemen)}</td>
                <td>${val(row.pol_ampas)}</td>
                <td>${val(row.zk_ampas)}</td>
                <td>${val(row.hk_tetes)}</td>
                <td>${val(row.iu_gkp)}</td>
                <td>${val(row.tebu)}</td>
                <td>${val(row.nm)}</td>
                <td>${val(row.nm_persen_tebu)}</td>
                <td>${val(row.imb)}</td>
            </tr>
        `;
        }
    }

    /* =========================================
       HELPERS
    ========================================= */

    function val(v) {
        return (v || v === 0) ?
            Number(v).toFixed(2) :
            '';
    }

    function formatJam(dateObj) {

        const h1 = String(dateObj.getHours()).padStart(2, '0');

        const next = new Date(dateObj);
        next.setHours(next.getHours() + 1);

        const h2 = String(next.getHours()).padStart(2, '0');

        return `${h1}:00 - ${h2}:00`;
    }
</script>

<?php include('footer.php'); ?>