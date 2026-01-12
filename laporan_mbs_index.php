<?php include('header.php'); ?>

<!-- VIEW -->
<div class="container-fluid">
    
    <h4 class="mb-3">Laporan MBS</h4>

    <div class="col-md-4">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <div class="font-weight-bold text-light text-uppercase mb-1">
                    Cetak Laporan
                </div>
                <hr>
                <form action="laporan_mbs_proses.php" method="POST" target="_blank">
                    <div class="form-group">
                        <div class="input-group">
                            <input id="date" name="date" type="date" class="form-control" value="<?= date('Y-m-d') ?>" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <select class="form-control" name="shift">
                                <option value="0">Harian</option>
                                <option value="1">Pagi</option>
                                <option value="2">Sore</option>
                                <option value="3">Malam</option>
                            </select>
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