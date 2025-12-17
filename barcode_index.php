<?php include('header.php'); ?>

<div class="container-fluid">
    <h4 class="mb-3">Barcode</h4>

    <div class="table-responsive">
        <table id="barcodeTable"
               class="table table-bordered table-striped text-dark"
               width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Material</th>
                    <th>Sampler</th>
                    <th>Timestamp Laporan</th>
                    <th>Timestamp Riil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(function () {
    $('#barcodeTable').DataTable({
        order: [[0, 'desc']],
        processing: true,
        serverSide: true,
        ajax: {
            url: 'barcode_fetch.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'material' },
            { data: 'user' },
            { data: 'created_at' },
            { data: 'timestamp_riil' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});

function deleteBarcode(id){
    if(confirm('Yakin hapus barcode ini?')){
        $.post('barcode_delete.php', {id:id}, function(){
            $('#barcodeTable').DataTable().ajax.reload();
        });
    }
}
</script>
