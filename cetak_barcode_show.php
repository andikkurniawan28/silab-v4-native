<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die('ID barcode tidak ditemukan');
}

$sample_id = $_GET['id'];

$q = $conn->query("
    SELECT a.*,
           m.name AS material_name,
           u.name AS user_name
    FROM analisa_off_farm_new a
    LEFT JOIN materials m ON m.id = a.material_id
    LEFT JOIN users u ON u.id = a.user_id
    WHERE a.id = '$sample_id'
    LIMIT 1
");

if ($q->num_rows == 0) {
    die('Data barcode tidak ditemukan');
}

$row = $q->fetch_assoc();
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
        <th width="30%"><h1>ID</h1></th>
        <th><h1><?= $row['id']; ?></h1></th>
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
        <td><h1>Material</h1></td>
        <td><h1><?= htmlspecialchars($row['material_name']); ?></h1></td>
    </tr>
    <tr>
        <td><h1>Sampler</h1></td>
        <td><h1><?= htmlspecialchars($row['user_name']); ?></h1></td>
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
