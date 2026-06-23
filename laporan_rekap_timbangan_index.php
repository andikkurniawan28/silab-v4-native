
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
    // 'Analis Off Farm', 
    // 'Mandor On Farm', 
    // 'Analis On Farm', 
    // 'Operator Pabrikasi',
    // 'Staff Teknik',
    // 'Staff Tanaman',
    // 'Staff TUK',
    // 'Direksi',
    // 'Tamu',
    ]);
include('header_rev.php');

$materials = $conn->query("
    SELECT id, name FROM materials ORDER BY name
");

?>

<!-- VIEW -->
<div class="container-fluid">
    
    <h4 class="mb-3">Laporan Rekap Timbangan</h4>

    <div class="col-md-4">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                <div class="font-weight-bold text-light text-uppercase mb-1">
                    Cetak Laporan
                </div>
                <hr>
                <form action="laporan_rekap_timbangan_proses.php" method="POST" target="_blank">
                    <div class="form-group">
                        <div class="input-group">
                            <select name="type" class="form-control" required>
                                <option value="rs_in">RS IN</option>
                                <option value="rs_out">RS OUT</option>
                                <option value="reject">Reject</option>
                                <option value="tetes">Tetes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <!-- Dari -->
                            <input id="date" name="from" type="date" class="form-control" value="2026-06-03" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <!-- Sampai Dengan -->
                            <input id="date" name="to" type="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="handling" value="print" class="btn btn-warning text-dark">Print <i class='fas fa-print'></i></button>
                        <button type="submit" name="handling" value="export" class="btn btn-warning text-dark">Export <i class='fas fa-download'></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include('footer.php'); ?>