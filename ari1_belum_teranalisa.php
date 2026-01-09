<?php
include('header.php');

/* ambil data */
$q = $conn->query("
    SELECT *
    FROM analisa_on_farms
    WHERE kartu_core IS NOT NULL
      AND rendemen_core IS NULL
    ORDER BY core_at DESC
");
?>

<div class="container-fluid">
    <h4 class="mb-3">ARI 1 Belum Teranalisa</h4>

    <div class="table-responsive">
        <table id="ari1Table" class="table table-bordered table-striped text-dark" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gelas</th>
                    <th>Timestamp</th>
                    <th>Antrian</th>
                    <th>Brix</th>
                    <th>Pol</th>
                    <th>Z</th>
                    <th>R</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($q->num_rows > 0): ?>
                <?php while ($row = $q->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['kartu_core'] ?? '-') ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['core_at'])) ?></td>
                        <td><?= $row['nomor_antrian'] ?></td>
                        <td><?= $row['brix_core'] ?? '-' ?></td>
                        <td><?= $row['pol_core'] ?? '-' ?></td>
                        <td><?= $row['pol_baca_core'] ?? '-' ?></td>
                        <td><?= $row['rendemen_core'] ?? '-' ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        Tidak ada data
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(document).ready(function () {
    $('#ari1Table').DataTable({
        order: [[2, 'desc']], // sort by Timestamp
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: {
                previous: "Prev",
                next: "Next"
            },
            zeroRecords: "Data tidak ditemukan"
        }
    });
});
</script>
