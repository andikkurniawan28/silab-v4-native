<?php
require_once 'db.php';

$material_id = intval($_GET['id'] ?? 0);

// ========================
// MATERIAL NAME
// ========================
$stmt = $conn->prepare("
    SELECT name 
    FROM materials 
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $material_id);
$stmt->execute();
$material = $stmt->get_result()->fetch_assoc();
$material_name = $material ? strtoupper($material['name']) : 'UNKNOWN MATERIAL';

// ========================
// AMBIL INDIKATOR (METHODS → INDICATORS)
// ========================
$indicatorStmt = $conn->prepare("
    SELECT indicators.name
    FROM methods
    JOIN indicators ON indicators.id = methods.indicator_id
    WHERE methods.material_id = ?
    ORDER BY methods.id ASC
");
$indicatorStmt->bind_param("i", $material_id);
$indicatorStmt->execute();
$indicators = $indicatorStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<?php include('header.php'); ?>


<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">
                <?= htmlspecialchars($material_name) ?>
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-dark table-hover table-striped data-table" width="100%">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Timestamp Laporan</th>
                            <th>Timestamp Riil</th>

                            <?php foreach ($indicators as $ind): ?>
                                <th><?= htmlspecialchars($ind['name']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="card-footer"></div>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    const materialId = <?= $material_id ?>;

    const indicatorColumns = [
        <?php foreach ($indicators as $ind): ?>
        // indicatorColumn("<?= str_replace($ind['name'], '_', ' '); ?>"),
        indicatorColumn("<?= str_replace(' ', '_', $ind['name']) ?>"),
        <?php endforeach; ?>
    ];

    $('.data-table').DataTable({
        order: [[0, 'desc']],
        processing: true,
        serverSide: true,
        ajax: {
            url: 'hasil_analisa_per_material_fetch.php?material_id=' + materialId,
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'created_at' },
            { data: 'timestamp_riil' },
            ...indicatorColumns
        ],
        dom: 'lBfrtip',
        buttons: [
            { extend: 'excelHtml5', title: 'Data Export - <?= $material_name ?>' },
            { extend: 'csvHtml5',   title: 'Data Export - <?= $material_name ?>' },
            { extend: 'pdfHtml5',   title: 'Data Export - <?= $material_name ?>' },
            { extend: 'print',      title: 'Data Export - <?= $material_name ?>' }
        ],
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        pageLength: 10
    });
});

function indicatorColumn(name) {
    return {
        data: function (row) {
            if (row.is_verified == 1) {
                return row[name] != 0 ? row[name] : '';
            }
            return '';
        },
        name: name
    };
}
</script>
