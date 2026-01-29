<?php
include 'db.php';

/* ================= INPUT ================= */
$kud_id = (int)($_POST['kud_id'] ?? 0);

if ($kud_id <= 0) {
    die('KUD tidak valid');
}

/* ================= AMBIL CODE DARI KUDS ================= */
$q = $conn->prepare("
    SELECT code, name
    FROM kuds
    WHERE id = ?
    LIMIT 1
");
$q->bind_param("i", $kud_id);
$q->execute();
$kud = $q->get_result()->fetch_assoc();

if (!$kud) {
    die('Data KUD tidak ditemukan');
}

$code = $kud['code'];   // 👈 PREFIX BARCODE
$kud_name = $kud['name'];

/* ================= AMBIL CODE TERAKHIR ================= */
$q = $conn->prepare("
    SELECT code
    FROM analisa_pendahuluans
    WHERE code LIKE CONCAT(?, '%')
    ORDER BY code DESC
    LIMIT 1
");
$q->bind_param("s", $code);
$q->execute();
$res = $q->get_result();

if ($row = $res->fetch_assoc()) {
    $last_code = $row['code'];
} else {
    $last_code = $code . '0';
}

/* ================= GENERATE BARCODE BARU ================= */
/*
  Contoh:
  code = A
  last_code = A12
  substr dari strlen(code) => 12
*/
$by_number   = substr($last_code, strlen($code));
$new_number  = (int)$by_number + 1;
$new_barcode = $code . '-' . $new_number;

/* ================= INSERT ================= */
$insert = $conn->prepare("
    INSERT INTO analisa_pendahuluans (code, kud_id)
    VALUES (?, ?)
");
$insert->bind_param("si", $new_barcode, $kud_id);
$insert->execute();

/* ================= AMBIL DATA YANG BARU DIINSERT ================= */
$last_id = $conn->insert_id;

$q = $conn->prepare("
    SELECT 
        ap.*,
        k.name AS kud_name
    FROM analisa_pendahuluans ap
    LEFT JOIN kuds k ON k.id = ap.kud_id
    WHERE ap.id = ?
    LIMIT 1
");
$q->bind_param("i", $last_id);
$q->execute();

$row = $q->get_result()->fetch_assoc();

/* ================= VIEW CETAK ================= */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sistem Informasi Laboratorium</title>

    <link href="/admin_template/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <style>
        h1 { font-size: 32px; margin: 0; }
        table { width: 100%; }
    </style>
</head>

<body>

<?php for($i=0; $i<3; $i++): ?>
<table class="table table-bordered text-dark">
    <tr>
        <th width="30%"><h1>Kode</h1></th>
        <th><h1><?= $row['code']; ?></h1></th>
    </tr>
    <tr>
        <td><h1>Barcode</h1></td>
        <td>
            <svg class="barcode"
                 jsbarcode-format="CODE128"
                 jsbarcode-value="<?= $row['id']; ?>"
                 jsbarcode-width="5"
                 jsbarcode-height="150"
                 jsbarcode-displayValue="false">
            </svg>
        </td>
    </tr>
    <tr>
        <td><h1>Wilayah</h1></td>
        <td><h1><?= htmlspecialchars($row['kud_name']); ?></h1></td>
    </tr>
    <tr>
        <td><h1>Timestamp</h1></td>
        <td><h1><?= $row['created_at']; ?></h1></td>
    </tr>
</table>
<br>
<?php endfor; ?>

<script>
    JsBarcode(".barcode").init();
    window.onload = function () {
        window.print();
    };
</script>

</body>
</html>

