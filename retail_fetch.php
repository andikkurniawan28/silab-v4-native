<?php
include('db.php');

$draw   = intval($_POST['draw'] ?? 1);
$start  = intval($_POST['start'] ?? 0);
$limit  = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

/* =========================================
   BASE QUERY — 1000 DATA TERAKHIR
========================================= */
$baseQuery = "(
    SELECT * 
    FROM retail 
    ORDER BY id DESC 
    LIMIT 1000
)";

/* =========================================
   TOTAL DATA (maks 1000)
========================================= */
$totalDataQuery = "SELECT COUNT(*) as total FROM $baseQuery as p";
$totalDataResult = $conn->query($totalDataQuery);
$totalData = $totalDataResult ? $totalDataResult->fetch_assoc()['total'] : 0;

/* =========================================
   FILTER UNTUK SEARCH
========================================= */
$whereClause = '';
$searchParams = [];

if (!empty($search)) {
    // Cari dalam format yang berbeda
    $searchDate = date('Y-m-d', strtotime(str_replace('-', '/', $search)));
    $searchDate2 = date('d-m-Y', strtotime(str_replace('-', '/', $search)));
    $searchNumeric = is_numeric($search) ? $search : '';

    $whereClause = " WHERE (
        p.created_at = '{$searchDate}' OR 
        p.created_at = '{$searchDate2}' OR
        p.jam = '{$searchNumeric}' OR
        p.mesin_aktif LIKE '%{$search}%' OR 
        p.value LIKE '%{$search}%' OR
        p.created_at LIKE '%{$search}%'
    )";
}

/* =========================================
   TOTAL DATA FILTERED
========================================= */
$totalFilteredQuery = "SELECT COUNT(*) as total FROM $baseQuery as p" . $whereClause;
$totalFilteredResult = $conn->query($totalFilteredQuery);
$totalFiltered = $totalFilteredResult ? $totalFilteredResult->fetch_assoc()['total'] : $totalData;

/* =========================================
   QUERY DATA DENGAN FILTER DAN PAGINATION
========================================= */
// Set default order
$orderColumn = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'desc';

// Mapping kolom untuk order
$columns = ['id', 'created_at', 'mesin_aktif', 'value'];
$orderBy = isset($columns[$orderColumn]) ? "p." . $columns[$orderColumn] : "p.id";

// Query utama dengan alias yang benar
$sql = "
    SELECT 
        p.id,
        p.created_at,
        p.mesin_aktif,
        p.value,
        p.berat_a,
        p.berat_b,
        p.berat_c,
        p.berat_d,
        p.berat_e,
        p.berat_f,
        p.created_at
    FROM $baseQuery as p
    $whereClause
    ORDER BY $orderBy $orderDir
    LIMIT $start, $limit
";

// Debug: Uncomment untuk melihat query
// echo "SQL Query: " . $sql;
// exit;

$q = $conn->query($sql);
if (!$q) {
    // Jika error, tampilkan error dan data kosong
    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => $conn->error
    ]);
    exit;
}

/* =========================================
   FORMAT DATA SESUAI SCHEMA BARU
========================================= */
$data = [];
if ($q && $q->num_rows > 0) {
    while ($r = $q->fetch_assoc()) {
        // Decode JSON untuk mesin_aktif
        $mesinAktifArray = [];
        if (!empty($r['mesin_aktif'])) {
            $decoded = json_decode($r['mesin_aktif'], true);
            $mesinAktifArray = is_array($decoded) ? $decoded : [$r['mesin_aktif']];
        }
        $mesinDisplay = !empty($mesinAktifArray) ? implode(', ', $mesinAktifArray) : '-';
        
        // Format created_at
        $created_atDisplay = !empty($r['created_at']) ? date('d-m-Y', strtotime($r['created_at'])) : '-';
        
        $data[] = [
            'id'          => $r['id'],
            'created_at'  => !empty($r['created_at']) ? date('d-m-Y H:i', strtotime($r['created_at'])) : '-',
            'mesin_aktif' => $mesinDisplay,
            'value'       => !empty($r['value']) ? number_format($r['value'], 0) : '-',
            'berat_a'          => $r['berat_a'],
            'berat_b'          => $r['berat_b'],
            'berat_c'          => $r['berat_c'],
            'berat_d'          => $r['berat_d'],
            'berat_e'          => $r['berat_e'],
            'berat_f'          => $r['berat_f'],
            'action'      => '
                <div class="btn-group" role="group">
                    <a href="retail_delete.php?id=' . $r['id'] . '"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm(\'Yakin hapus data ini?\')">
                       <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
            '
        ];
    }
}

/* =========================================
   OUTPUT JSON
========================================= */
echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => (int)$totalData,
    'recordsFiltered' => (int)$totalFiltered,
    'data'            => $data
]);

// Tutup koneksi jika diperlukan
if (isset($conn) && $conn) {
    $conn->close();
}
?>