<?php
include('db.php');

/**
 * AJAX ambil Pol & Kadar Air
 */
if(isset($_GET['pol'])){
    $id = intval($_GET['id']);
    $q = $conn->query("
        SELECT Pol, `%Air`
        FROM analisa_off_farm_new
        WHERE id = $id
        LIMIT 1
    ");
    $r = $q->fetch_assoc();
    echo json_encode([
        'pol_baca'=>floatval($r['Pol']??0),
        'kadar_air'=>floatval($r['%Air']??0)
    ]);
    exit;
}

/**
 * DATATABLE
 */
$limit=$_POST['length'];
$start=$_POST['start'];

$total=$conn->query("
    SELECT COUNT(*)
    FROM analisa_off_farm_new
    WHERE material_id=400
")->fetch_row()[0];

$q=$conn->query("
    SELECT id,created_at,Pol_Ampas,`%Air`,`%Zk`
    FROM analisa_off_farm_new
    WHERE material_id=400
    ORDER BY id DESC
    LIMIT $start,$limit
");

$data=[];
while($r=$q->fetch_assoc()){
    $data[]=[
        'id'=>$r['id'],
        'created_at'=>$r['created_at'],
        'pol_ampas'=>$r['Pol_Ampas']??'-',
        'air'=>$r['%Air']??'-',
        'zk'=>$r['%Zk']??'-',
        'action'    => '
            <a href="analisa_ampas_john_payne_delete.php?id='.$r['id'].'"
               class="btn btn-danger btn-sm"
               onclick="return confirm(\'Hapus data?\')">
               Hapus
            </a>
        '
    ];
}

echo json_encode([
    "draw"=>intval($_POST['draw']),
    "recordsTotal"=>$total,
    "recordsFiltered"=>$total,
    "data"=>$data
]);
