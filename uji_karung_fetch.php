<?php
include('db.php');

$draw   = intval($_POST['draw'] ?? 1);
$start  = intval($_POST['start'] ?? 0);
$limit  = intval($_POST['length'] ?? 10);

/* =========================================
   BASE TABLE — 1000 DATA TERAKHIR
========================================= */
$baseTable = "
    (
        SELECT
            id,
            tanggal,
            kedatangan,
            batch,
            nomor,
            denier_nilai,
            (
                p_ket_outer *
                l_ket_outer *
                p_ket_inner *
                l_ket_inner *
                berat_outer_ket *
                berat_inner_ket *
                tebal_outer_ket *
                tebal_inner_ket *
                mesh_ket_alas *
                mesh_ket_tinggi *
                denier_ket
            ) AS status
        FROM uji_karungs
        ORDER BY id DESC
        LIMIT 1000
    ) AS u
";

/* =========================================
   TOTAL DATA (maks 1000)
========================================= */
$totalData = $conn->query("
    SELECT COUNT(*)
    FROM $baseTable
")->fetch_row()[0];

/* =========================================
   QUERY DATA
========================================= */
$q = $conn->query("
    SELECT *
    FROM $baseTable
    ORDER BY id DESC
    LIMIT $start, $limit
");

/* =========================================
   FORMAT DATA
========================================= */
$data = [];
while ($row = $q->fetch_assoc()) {

    $statusBadge = $row['status']
        ? '<span class="badge bg-success text-light">OK</span>'
        : '<span class="badge bg-danger text-light">NG</span>';

    $data[] = [
        'id'           => $row['id'],
        'tanggal'      => date('d-m-Y', strtotime($row['tanggal'])),
        'kedatangan'   => date('d-m-Y', strtotime($row['kedatangan'])),
        'batch'        => $row['batch'],
        'nomor'        => $row['nomor'],
        'denier_nilai' => $row['denier_nilai'],
        'status'       => $statusBadge,
        'action'       => '
            <a href="uji_karung_report.php?tanggal='.$row['tanggal'].'&kedatangan='.$row['kedatangan'].'&batch='.$row['batch'].'"
               class="btn btn-info btn-sm">
               Report
            </a>

            <a href="uji_karung_delete.php?id='.$row['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data?\')">
               Hapus
            </a>
        '
    ];
}

/* =========================================
   OUTPUT JSON
========================================= */
echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => $totalData,
    'recordsFiltered' => $totalData,
    'data'            => $data
]);
