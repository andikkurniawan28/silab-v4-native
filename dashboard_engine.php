<?php
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require 'db.php';

/* =====================================================
   1. INPUT & SHIFT TIME
===================================================== */
$date = $_GET['date'] ?? date('Y-m-d');

// SHIFT: 06:00 hari ini → 05:59:59 besok
$start = date('Y-m-d 06:00:00', strtotime($date));
$end   = date('Y-m-d 05:59:59', strtotime($date . ' +1 day'));

/* =====================================================
   2. HELPER QUERY
===================================================== */
function getHourlyAvg($conn, $sql, $start, $end) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $start, $end);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/* =====================================================
   3. CONTAINER OUTPUT
===================================================== */
$dashboard = [
    'brix_npp'        => [],
    'pol_npp'         => [],
    'hk_npp'          => [],
    'rendemen_npp'    => [],
    'hk_tetes'        => [],
    'iu_gkp'          => [],
    'tebu_tergiling'  => [],
    'flow_nm'         => [],
    'flow_imb'        => [],
];

/* =====================================================
   4. NPP (material_id = 3)
===================================================== */

// %Brix
$dashboard['brix_npp'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        AVG(`%Brix`) AS value
    FROM analisa_off_farm_new
    WHERE material_id = 3
      AND is_verified = 1
      AND `%Brix` IS NOT NULL
      AND created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

// %Pol
$dashboard['pol_npp'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        AVG(`%Pol`) AS value
    FROM analisa_off_farm_new
    WHERE material_id = 3
      AND is_verified = 1
      AND `%Pol` IS NOT NULL
      AND created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

// HK
$dashboard['hk_npp'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        AVG(HK) AS value
    FROM analisa_off_farm_new
    WHERE material_id = 3
      AND is_verified = 1
      AND HK IS NOT NULL
      AND created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

// %R
$dashboard['rendemen_npp'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        AVG(`%R`) AS value
    FROM analisa_off_farm_new
    WHERE material_id = 3
      AND is_verified = 1
      AND `%R` IS NOT NULL
      AND created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

/* =====================================================
   5. HK TETES (material_id = 64)
===================================================== */
$dashboard['hk_tetes'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        AVG(HK) AS value
    FROM analisa_off_farm_new
    WHERE material_id = 64
      AND is_verified = 1
      AND HK IS NOT NULL
      AND HK != 0
      AND created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

/* =====================================================
   6. IU GKP (material_id = 67)
===================================================== */
$dashboard['iu_gkp'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        AVG(IU) AS value
    FROM analisa_off_farm_new
    WHERE material_id = 67
      AND is_verified = 1
      AND IU IS NOT NULL
      AND IU != 0
      AND created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

/* =====================================================
   7. Pol Ampas (material_id = 12)
===================================================== */
$dashboard['pol_ampas'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        AVG(Pol_Ampas) AS value
    FROM analisa_off_farm_new
    WHERE material_id = 12
      AND is_verified = 1
      AND Pol_Ampas IS NOT NULL
      AND Pol_Ampas != 0
      AND created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

/* =====================================================
   7. TABLE BALANCES
===================================================== */

// Tebu tergiling
$dashboard['tebu_tergiling'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        SUM(tebu) AS value
    FROM balances
    WHERE created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

// Flow NM
$dashboard['flow_nm'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        AVG(flow_nm) AS value
    FROM balances
    WHERE created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

/* =====================================================
   8. IMBIBITION
===================================================== */
$dashboard['flow_imb'] = getHourlyAvg($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS created_at,
        AVG(flow_imb) AS value
    FROM imbibitions
    WHERE created_at BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
    ORDER BY created_at ASC
", $start, $end);

/* =====================================================
   9. OUTPUT JSON
===================================================== */
echo json_encode($dashboard, JSON_THROW_ON_ERROR);
exit;