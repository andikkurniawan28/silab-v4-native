<?php
require_once('db.php');
require_once('db_packer2.php');

function determineTimeRange($date, $shift)
{
    switch ($shift) {
        case 0:
            $current = "$date 06:00";
            $tomorrow = date("Y-m-d H:i", strtotime("$current +24 hours"));
            break;
        case 1:
            $current = "$date 05:00";
            $tomorrow = date("Y-m-d H:i", strtotime("$current +8 hours"));
            break;
        case 2:
            $current = "$date 13:00";
            $tomorrow = date("Y-m-d H:i", strtotime("$current +8 hours"));
            break;
        case 3:
            $current = "$date 21:00";
            $tomorrow = date("Y-m-d H:i", strtotime("$current +8 hours"));
            break;
    }
    return compact('current', 'tomorrow');
}

function serveMandor($conn, $date, $shift)
{
    $time = determineTimeRange($date, $shift);

    $stmt = $conn->prepare("
        SELECT
            a.*,
            m.name AS material_name,
            s.name AS station_name
        FROM analisa_off_farm_new a
        JOIN materials m
            ON m.id = a.material_id
        JOIN stations s
            ON s.id = m.station_id
        WHERE a.created_at >= ?
          AND a.created_at < ?
          AND a.is_verified = 1
    ");

    $stmt->bind_param("ss", $time['current'], $time['tomorrow']);
    $stmt->execute();

    $result = $stmt->get_result();

    $data = [];

    while ($row = $result->fetch_assoc()) {

        $station  = $row['station_name'];
        $material = $row['material_name'];

        foreach ($row as $column => $value) {

            if (in_array($column, [
                'id',
                'material_id',
                'material_name',
                'station_name',
                'created_at',
                'updated_at',
                'is_verified',
                'volume',
                'remark'
            ])) {
                continue;
            }

            if (!is_numeric($value) || $value == 0) {
                continue;
            }

            $indicator = str_replace('_', ' ', $column);

            if (!isset($data[$station][$material][$indicator])) {
                $data[$station][$material][$indicator] = [
                    'sum' => 0,
                    'count' => 0
                ];
            }

            $data[$station][$material][$indicator]['sum'] += $value;
            $data[$station][$material][$indicator]['count']++;
        }
    }

    foreach ($data as &$stations) {
        foreach ($stations as &$materials) {
            foreach ($materials as &$indicator) {
                $indicator = round($indicator['sum'] / $indicator['count'], 2);
            }
        }
    }

    return $data;
}

$time = determineTimeRange($_POST['date'], $_POST['shift']);
$data = serveMandor($conn,$_POST['date'],$_POST['shift']);

$request = new stdClass();
$request->shift = $_POST['shift'];
$request->date = $_POST['date'];

if($_POST['handling'] == 'export'){
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=Laporan_Off_Farm_' . $_POST["date"] . '.xls');
}

?>


<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

        <title>Laporan Mandor</title>

        <link rel="icon" type="image/png" href="/Silab-V3/public/admin_template/img/QC.png"/>
        <link href="/Silab-V3/public/admin_template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="/Silab-V3/public/admin_template/css/sb-admin-2.min.css" rel="stylesheet">
        <link href="/Silab-V3/public/admin_template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	</head>

	<body>

        <?php
            if($_POST['handling'] == 'export'){
                header("Content-type: application/vnd-ms-excel");
                header("Content-Disposition: attachment; filename=Laporan_Silab_" . $request->date . ".xls");
            }
        ?>

        <div class="wrapper">

            <section class="invoice">

                <br></br>

                <div class="row d-flex justify-content-center text-dark text-dark">
                    <div class="col-10 table-responsive">
                        <table border='1' cellpadding='0' width='100%'>
                            <thead>
                                <tr>
                                    <th rowspan='2'><img class='rounded mx-auto d-block' src='/Silab-V3/public/admin_template/img/ka.jpg' width="100" height="100"></img></th>
                                    <th colspan='3' class='text-center'>
                                        <H6><STRONG>PT. KEBON AGUNG UNIT PABRIK GULA KEBON AGUNG</STRONG></H6>
                                        <p><SMALL>FORMULIR</SMALL></p>
                                        <H4><STRONG>LAPORAN MANDOR OFF-FARM</STRONG></H4>
                                    </th>
                                    <th rowspan='2'><img class='rounded mx-auto d-block' src='/Silab-V3/public/admin_template/img/QC.png' width="100" height="100"></img></th>
                                </tr>
                                <tr>
                                    <th class='text-center'>No Dok : KBA/FRM/QC/005-00</th>
                                    <th class='text-center'>Tanggal : <?= date('d-m-Y', strtotime($request->date)) ?></th>
                                    <th class='text-center'>
                                        
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            <br>

			<div class='row d-flex justify-content-center text-dark'>

			<div class="col-5 table-responsive">

                <h3 class="page-header">Stasiun Gilingan</h3>

				<table width='100%' border='1' cellpadding='5'>
				  <thead>
					<tr>
						<th bgcolor='pink'>Uraian</th>
						<th bgcolor='pink'>Brix</th>
						<th bgcolor='pink'>Pol</th>
						<th bgcolor='pink'>HK</th>
						<th bgcolor='pink'>pH</th>
						<th bgcolor='pink'>%R</th>
					</tr>
				  </thead>
				  <tbody>
                        <tr>
                            <td>Nira Gilingan 1</td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 1"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 1"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 1"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 1"]["pH"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 1"]["%R"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Nira Gilingan 2</td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 2"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 2"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 2"]["HK"] ?? "" ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Nira Gilingan 3</td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 3"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 3"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 3"]["HK"] ?? "" ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Nira Gilingan 4</td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 4"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 4"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 4"]["HK"] ?? "" ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Nira Gilingan 5</td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 5"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 5"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gilingan"]["Nira Gilingan 5"]["HK"] ?? "" ?></td>
                            <td></td>
                            <td></td>
                        </tr>
					</tbody>
				</table>

                <br>

                <h3 class="page-header">Stasiun Pemurnian</h3>

				<table width='100%' border='1' cellpadding='5'>
				  <thead>
					<tr>
						<th bgcolor='pink'>Uraian</th>
						<th bgcolor='pink'>Brix</th>
						<th bgcolor='pink'>Pol</th>
						<th bgcolor='pink'>HK</th>
						<th bgcolor='pink'>ICUMSA</th>
						<th bgcolor='pink'>CAO</th>
						<th bgcolor='pink'>pH</th>
						<th bgcolor='pink'>Turbidity</th>
						<th bgcolor='pink'>%Ampas</th>
					</tr>
				  </thead>
				  <tbody>
						<tr>
                            <td>Nira Mentah</td>
                            <td><?= $data["Pemurnian"]["Nira Mentah"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah"]["HK"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah"]["IU"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah"]["CaO"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah"]["pH"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah"]["Turbidity"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["%Ampas Nira Mentah"]["%Ampas"] ?? "" ?></td>
					    </tr>
						<tr>
                            <td>Nira Tapis</td>
                            <td><?= $data["Pemurnian"]["Nira Tapis"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Tapis"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Tapis"]["HK"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Tapis"]["IU"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Tapis"]["CaO"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Tapis"]["pH"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Tapis"]["Turbidity"] ?? "" ?></td>
                            <td></td>
					    </tr>
						<tr>
                            <td>Nira Encer</td>
                            <td><?= $data["Pemurnian"]["Nira Encer"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Encer"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Encer"]["HK"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Encer"]["IU"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Encer"]["CaO"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Encer"]["pH"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Encer"]["Turbidity"] ?? "" ?></td>
                            <td></td>
					    </tr>
						<tr>
                            <td>Nira Defekasi</td>
                            <td><?= $data["Pemurnian"]["Nira Defekasi"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Defekasi"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Defekasi"]["HK"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Defekasi"]["IU"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Defekasi"]["CaO"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Defekasi"]["pH"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Defekasi"]["Turbidity"] ?? "" ?></td>
                            <td></td>
					    </tr>
						<tr>
                            <td>Nira Mentah Phospat</td>
                            <td><?= $data["Pemurnian"]["Nira Mentah Penambahan Phospat"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah Penambahan Phospat"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah Penambahan Phospat"]["HK"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah Penambahan Phospat"]["IU"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah Penambahan Phospat"]["CaO"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah Penambahan Phospat"]["pH"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Nira Mentah Penambahan Phospat"]["Turbidity"] ?? "" ?></td>
                            <td></td>
					    </tr>
				  </tbody>
				</table>

                <br>

                <h3 class="page-header">Blotong & Cake</h3>

				<table width='100%' border='1' cellpadding='5'>
                    <thead>
                        <tr>
                            <th bgcolor='pink'>Uraian</th>
                            <th bgcolor='pink'>Pol</th>
                            <th bgcolor='pink'>Kadar Air</th>
                        </tr>
                        <tr>
                            <td>Blotong RVF Timur</td>
                            <td><?= $data["Pemurnian"]["Blotong Truk RVF Timur"]["Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Blotong Truk RVF Timur"]["%Air"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Blotong RVF Barat</td>
                            <td><?= $data["Pemurnian"]["Blotong Truk RVF Barat"]["Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Blotong Truk RVF Barat"]["%Air"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Blotong RVF 1</td>
                            <td><?= $data["Pemurnian"]["Blotong RVF 1"]["Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Blotong RVF 1"]["%Air"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Blotong RVF 2</td>
                            <td><?= $data["Pemurnian"]["Blotong RVF 2"]["Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Blotong RVF 2"]["%Air"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Blotong RVF 3</td>
                            <td><?= $data["Pemurnian"]["Blotong RVF 3"]["Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Blotong RVF 3"]["%Air"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Blotong RVF 4</td>
                            <td><?= $data["Pemurnian"]["Blotong RVF 4"]["Pol"] ?? "" ?></td>
                            <td><?= $data["Pemurnian"]["Blotong RVF 4"]["%Air"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Filter Cake Head</td>
                            <td><?= $data["DRK"]["Cake Head"]["Pol"] ?? "" ?></td>
                            <td><?= $data["DRK"]["Cake Head"]["%Air"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Filter Cake Mid</td>
                            <td><?= $data["DRK"]["Cake Mid"]["Pol"] ?? "" ?></td>
                            <td><?= $data["DRK"]["Cake Mid"]["%Air"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Filter Cake End</td>
                            <td><?= $data["DRK"]["Cake End"]["Pol"] ?? "" ?></td>
                            <td><?= $data["DRK"]["Cake End"]["%Air"] ?? "" ?></td>
                        </tr>
                    </thead>
                </table>

                <br>

				<h3 class="page-header">Stroop</h3>

				<table width='100%' border='1' cellpadding='6'>
				  <thead>
				  	<tr>
						<th bgcolor='pink'>Uraian</th>
						<th bgcolor='pink'>Brix</th>
						<th bgcolor='pink'>Pol</th>
						<th bgcolor='pink'>HK</th>
						<th bgcolor='pink'>ICUMSA</th>
				 	</tr>
				  	</thead>

				  	<tbody>
				  	<tr>
						<td>Magma RS</td>
						<td><?= $data["Stroop"]["Magma RS"]["%Brix"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Magma RS"]["%Pol"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Magma RS"]["HK"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Magma RS"]["IU"] ?? "" ?></td>
					</tr>
				  	<tr>
						<td>Magma A Raw</td>
						<td><?= $data["Stroop"]["Magma A RAW"]["%Brix"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Magma A RAW"]["%Pol"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Magma A RAW"]["HK"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Magma A RAW"]["IU"] ?? "" ?></td>
					</tr>
				  	<tr>
						<td>Magma Afinasi</td>
						<td><?= $data["Stroop"]["Magma Afinasi"]["%Brix"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Magma Afinasi"]["%Pol"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Magma Afinasi"]["HK"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Magma Afinasi"]["IU"] ?? "" ?></td>
					</tr>
                    <tr>
                      <td>Magma C</td>
                      <td><?= $data["Stroop"]["MAGMA C"]["%Brix"] ?? "" ?></td>
                      <td><?= $data["Stroop"]["MAGMA C"]["%Pol"] ?? "" ?></td>
                      <td><?= $data["Stroop"]["MAGMA C"]["HK"] ?? "" ?></td>
                      <td><?= $data["Stroop"]["MAGMA C"]["IU"] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td>Magma D1</td>
                        <td><?= $data["Stroop"]["Magma D1"]["%Brix"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["Magma D1"]["%Pol"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["Magma D1"]["HK"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["Magma D1"]["IU"] ?? "" ?></td>
                    </tr>
				  	{{-- <tr>
						<td>Klare SHS</td>
						<td><?= $data["Stroop"]["Klare SHS"]["%Brix"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Klare SHS"]["%Pol"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Klare SHS"]["HK"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Klare SHS"]["IU"] ?? "" ?></td>
					</tr> --?>
                    <tr>
                      <td>Klare D</td>
                      <td><?= $data["Stroop"]["Klare D"]["%Brix"] ?? "" ?></td>
                      <td><?= $data["Stroop"]["Klare D"]["%Pol"] ?? "" ?></td>
                      <td><?= $data["Stroop"]["Klare D"]["HK"] ?? "" ?></td>
                      <td><?= $data["Stroop"]["Klare D"]["IU"] ?? "" ?></td>
                    </tr>
				  	<tr>
						<td>Klare RS</td>
						<td><?= $data["Stroop"]["Klare RS"]["%Brix"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Klare RS"]["%Pol"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Klare RS"]["HK"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Klare RS"]["IU"] ?? "" ?></td>
					</tr>
				  	{{-- <tr>
						<td>Sirup Afinasi</td>
						<td><?= $data["Stroop"]["Syrup Afinasi (Klare RS)"]["%Brix"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Syrup Afinasi (Klare RS)"]["%Pol"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Syrup Afinasi (Klare RS)"]["HK"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Syrup Afinasi (Klare RS)"]["IU"] ?? "" ?></td>
					</tr> --?>
				  	<tr>
						<td>Stroop A</td>
						<td><?= $data["Stroop"]["Stroop A"]["%Brix"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Stroop A"]["%Pol"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Stroop A"]["HK"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Stroop A"]["IU"] ?? "" ?></td>
					</tr>
				  	<tr>
						<td>Stroop C</td>
						<td><?= $data["Stroop"]["Stroop C"]["%Brix"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Stroop C"]["%Pol"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Stroop C"]["HK"] ?? "" ?></td>
						<td><?= $data["Stroop"]["Stroop C"]["IU"] ?? "" ?></td>
					</tr>
                    <tr>
                        <td>R1 Mol</td>
                        <td><?= $data["Stroop"]["R1 Mol"]["%Brix"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["R1 Mol"]["%Pol"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["R1 Mol"]["HK"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["R1 Mol"]["IU"] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td>R2 Mol</td>
                        <td><?= $data["Stroop"]["R2 Mol"]["%Brix"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["R2 Mol"]["%Pol"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["R2 Mol"]["HK"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["R2 Mol"]["IU"] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td>Remelter CD</td>
                        <td><?= $data["Stroop"]["Remelter CD"]["%Brix"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["Remelter CD"]["%Pol"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["Remelter CD"]["HK"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["Remelter CD"]["IU"] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td>Tetes Puteran</td>
                        <td><?= $data["Stroop"]["Tetes Puteran"]["%Brix"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["Tetes Puteran"]["%Pol"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["Tetes Puteran"]["HK"] ?? "" ?></td>
                        <td><?= $data["Stroop"]["Tetes Puteran"]["IU"] ?? "" ?></td>
                    </tr>
					</tbody>
				</table>

                <br>

                <h3 class="page-header">Stasiun Ketel</h3>

				<table width='100%' border='1' cellpadding='6'>
				  <thead>
				  	<tr>
						<th bgcolor='pink'>Uraian</th>
						<th bgcolor='pink'>TDS</th>
						<th bgcolor='pink'>pH</th>
						<th bgcolor='pink'>Hardness</th>
						<th bgcolor='pink'>Phospat</th>
				 	</tr>
				  	</thead>
				  	<tbody>
                        <tr>
                            <td>Jiangxin Jianglin</td>
                            <td><?= $data["Ketel"]["Jiangxin Jianglin"]["TDS"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Jiangxin Jianglin"]["pH"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Jiangxin Jianglin"]["Sadah"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Jiangxin Jianglin"]["P2O5"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Yoshimine 1</td>
                            <td><?= $data["Ketel"]["Yoshimine 1"]["TDS"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Yoshimine 1"]["pH"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Yoshimine 1"]["Sadah"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Yoshimine 1"]["P2O5"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Yoshimine 2</td>
                            <td><?= $data["Ketel"]["Yoshimine 2"]["TDS"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Yoshimine 2"]["pH"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Yoshimine 2"]["Sadah"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Yoshimine 2"]["P2O5"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>WTP</td>
                            <td><?= $data["Ketel"]["WTP"]["TDS"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["WTP"]["pH"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["WTP"]["Sadah"] ?? "" ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>HW</td>
                            <td><?= $data["Ketel"]["HW"]["TDS"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["HW"]["pH"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["HW"]["Sadah"] ?? "" ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Daert JJ</td>
                            <td><?= $data["Ketel"]["Daert JJ"]["TDS"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Daert JJ"]["pH"] ?? "" ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Daert Yoshimine1</td>
                            <td><?= $data["Ketel"]["Daert Yoshimine1"]["TDS"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Daert Yoshimine1"]["pH"] ?? "" ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Daert Yoshimine2</td>
                            <td><?= $data["Ketel"]["Daert Yoshimine2"]["TDS"] ?? "" ?></td>
                            <td><?= $data["Ketel"]["Daert Yoshimine2"]["pH"] ?? "" ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

			</div>

			<div class="col-5 table-responsive">

                <h3 class="page-header">Ampas Gilingan</h3>

                <table width='100%' border='1' cellpadding='5'>
                <thead>
                    <tr>
                        <th bgcolor='pink'>Uraian</th>
                        <th bgcolor='pink'>Pol</th>
                        <th bgcolor='pink'>ZK</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ampas Gilingan 1</td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 1"]["Pol Ampas"] ?? "" ?></td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 1"]["%Zk"] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td>Ampas Gilingan 2</td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 2"]["Pol Ampas"] ?? "" ?></td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 2"]["%Zk"] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td>Ampas Gilingan 3</td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 3"]["Pol Ampas"] ?? "" ?></td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 3"]["%Zk"] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td>Ampas Gilingan 4</td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 4"]["Pol Ampas"] ?? "" ?></td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 4"]["%Zk"] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td>Ampas Gilingan 5</td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 5"]["Pol Ampas"] ?? "" ?></td>
                        <td><?= $data["Gilingan"]["Ampas Gilingan 5"]["%Zk"] ?? "" ?></td>
                    </tr>
                </tbody>
                </table>

                <br>

				<h3 class="page-header">Stasiun DRK</h3>

				<table width='100%' border='1' cellpadding='5'>
				  <thead>
					<tr>
						<th bgcolor='pink'>Uraian</th>
						<th bgcolor='pink'>Brix</th>
						<th bgcolor='pink'>Pol</th>
						<th bgcolor='pink'>HK</th>
						<th bgcolor='pink'>ICUMSA</th>
						<th bgcolor='pink'>CaO</th>
						<th bgcolor='pink'>pH</th>
						<th bgcolor='pink'>Turbidity</th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
                        <td>Remelter</td>
                        <td><?= $data["DRK"]["Remelter"]["%Brix"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Remelter"]["%Pol"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Remelter"]["HK"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Remelter"]["IU"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Remelter"]["CaO"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Remelter"]["pH"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Remelter"]["Turbidity"] ?? "" ?></td>
					</tr>
					<tr>
                        <td>Reaction Tank</td>
                        <td><?= $data["DRK"]["Reaction Tank"]["%Brix"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Reaction Tank"]["%Pol"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Reaction Tank"]["HK"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Reaction Tank"]["IU"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Reaction Tank"]["CaO"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Reaction Tank"]["pH"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Reaction Tank"]["Turbidity"] ?? "" ?></td>
					</tr>
					<tr>
                        <td>Carbonated</td>
                        <td><?= $data["DRK"]["Carbonated"]["%Brix"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Carbonated"]["%Pol"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Carbonated"]["HK"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Carbonated"]["IU"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Carbonated"]["CaO"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Carbonated"]["pH"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Carbonated"]["Turbidity"] ?? "" ?></td>
					</tr>
					<tr>
                        <td>Clear Liquor</td>
                        <td><?= $data["DRK"]["Clear Liquor"]["%Brix"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Clear Liquor"]["%Pol"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Clear Liquor"]["HK"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Clear Liquor"]["IU"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Clear Liquor"]["CaO"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Clear Liquor"]["pH"] ?? "" ?></td>
                        <td><?= $data["DRK"]["Clear Liquor"]["Turbidity"] ?? "" ?></td>
					</tr>
				  </tbody>
				</table>

                <br>

                <h3 class="page-header">Stasiun Penguapan</h3>

                    <table width='100%' border='1' cellpadding='5'>
                    <thead>
                        <tr>
                            <th bgcolor='pink'>Uraian</th>
                            <th bgcolor='pink'>Brix</th>
                            <th bgcolor='pink'>Pol</th>
                            <th bgcolor='pink'>HK</th>
                            <th bgcolor='pink'>ICUMSA</th>
                            <th bgcolor='pink'>CaO</th>
                            <th bgcolor='pink'>pH</th>
                            <th bgcolor='pink'>Turbidity</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>Nira Kental 1</td>
                            <td><?= $data["Penguapan"]["Nira Kental 1"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 1"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 1"]["HK"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 1"]["IU"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 1"]["CaO"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 1"]["pH"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 1"]["Turbidity"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Nira Kental 2</td>
                            <td><?= $data["Penguapan"]["Nira Kental 2"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 2"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 2"]["HK"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 2"]["IU"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 2"]["CaO"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 2"]["pH"] ?? "" ?></td>
                            <td><?= $data["Penguapan"]["Nira Kental 2"]["Turbidity"] ?? "" ?></td>
                        </tr>
                        </tbody>
                    </table>

                    <br>

                    <h3 class="page-header">Stasiun Masakan</h3>

                    <table width='100%' border='1' cellpadding='5'>
                        <thead>
                            <tr>
                                <th bgcolor='pink'>Uraian</th>
                                <th bgcolor='pink'>Brix</th>
                                <th bgcolor='pink'>Pol</th>
                                <th bgcolor='pink'>HK</th>
                                <th bgcolor='pink'>ICUMSA</th>
                            </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>Masakan A</td>
                            <td><?= $data["Masakan"]["Masakan A"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan A"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan A"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan A"]["IU"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Masakan A Raw</td>
                            <td><?= $data["Masakan"]["Masakan A Raw"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan A Raw"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan A Raw"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan A Raw"]["IU"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Masakan C</td>
                            <td><?= $data["Masakan"]["Masakan C"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan C"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan C"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan C"]["IU"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Masakan D2</td>
                            <td><?= $data["Masakan"]["Masakan D2"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan D2"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan D2"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan D2"]["IU"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Masakan R1</td>
                            <td><?= $data["Masakan"]["Masakan R1"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan R1"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan R1"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Masakan R1"]["IU"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>CVP A</td>
                            <td><?= $data["Masakan"]["CVP A"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["CVP A"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["CVP A"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["CVP A"]["IU"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>CVP C</td>
                            <td><?= $data["Masakan"]["CVP C"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["CVP C"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["CVP C"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["CVP C"]["IU"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>CVP D</td>
                            <td><?= $data["Masakan"]["CVP D"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["CVP D"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["CVP D"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["CVP D"]["IU"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Einwuurf C</td>
                            <td><?= $data["Masakan"]["Einwuurf C"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Einwuurf C"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Einwuurf C"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Einwuurf C"]["IU"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Einwuurf D</td>
                            <td><?= $data["Masakan"]["Einwuurf D"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Einwuurf D"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Einwuurf D"]["HK"] ?? "" ?></td>
                            <td><?= $data["Masakan"]["Einwuurf D"]["IU"] ?? "" ?></td>
                        </tr>
                        </tbody>
                    </table>

				<br>

				<h3 class="page-header">Gula in Proses</h3>

				<table width='100%' border='1' cellpadding='5'>
				    <thead>
                        <tr>
                            <th bgcolor='pink'>Uraian</th>
                            <th bgcolor='pink'>ICUMSA</th>
                            <th bgcolor='pink'>Kadar Air</th>
                            <th bgcolor='pink'>Brix</th>
                            <th bgcolor='pink'>Pol</th>
                            <th bgcolor='pink'>HK</th>
                            <th bgcolor='pink'>BJB</th>
                            <th bgcolor='pink'>SO<sub>2</sub></th>
                        </tr>
				    </thead>
				    <tbody>
                        <tr>
                            <td>Gula RS Silo</td>
                            <td><?= $data["Gula"]["Gula RS Silo ( RS IN )"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Silo ( RS IN )"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Silo ( RS IN )"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Silo ( RS IN )"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Silo ( RS IN )"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Silo ( RS IN )"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Silo ( RS IN )"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula RS Produk</td>
                            <td><?= $data["Gula"]["Gula RS Produk"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Produk"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Produk"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Produk"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Produk"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Produk"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula RS Produk"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula SHS</td>
                            <td><?= $data["Gula"]["Gula SHS"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula SHS"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula SHS"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula SHS"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula SHS"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula SHS"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula SHS"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula A</td>
                            <td><?= $data["Gula"]["Gula A"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula R1</td>
                            <td><?= $data["Gula"]["Gula R1"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R1"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R1"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R1"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R1"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R1"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R1"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula R2</td>
                            <td><?= $data["Gula"]["Gula R2"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R2"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R2"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R2"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R2"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R2"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula R2"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula A Raw</td>
                            <td><?= $data["Gula"]["Gula A Raw"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A Raw"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A Raw"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A Raw"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A Raw"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A Raw"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula A Raw"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula C</td>
                            <td><?= $data["Gula"]["Gula C"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula C"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula C"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula C"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula C"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula C"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula C"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula D1</td>
                            <td><?= $data["Gula"]["Gula D1"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D1"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D1"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D1"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D1"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D1"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D1"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula D2</td>
                            <td><?= $data["Gula"]["Gula D2"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D2"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D2"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D2"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D2"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D2"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula D2"]["SO2"] ?? "" ?></td>
                        </tr>
                        <tr>
                            <td>Gula Conti RS</td>
                            <td><?= $data["Gula"]["Gula Afinasi (Gula Conti RS)"]["IU"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula Afinasi (Gula Conti RS)"]["%Air"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula Afinasi (Gula Conti RS)"]["%Brix"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula Afinasi (Gula Conti RS)"]["%Pol"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula Afinasi (Gula Conti RS)"]["HK"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula Afinasi (Gula Conti RS)"]["BJB"] ?? "" ?></td>
                            <td><?= $data["Gula"]["Gula Afinasi (Gula Conti RS)"]["SO2"] ?? "" ?></td>
                        </tr>
				    </tbody>
				</table>

			  </div>
			  <!-- /.col -->
			</div>

			<div class="row d-flex justify-content-center text-dark">
			  <!-- accepted payments column -->
			  <div class="col-5">
                    <br><br>
					<p class='text-center'>Mengetahui,</p>
					<br></br><br></br>
					<br></br>
					<p class='text-center'><strong><u>--------</u></strong></p>
					<p class='text-center'>Chemiker Jaga</p>
			  </div>
			  <div class="col-5">
                    <br><br>
					<p class='text-center'>Kebonagung, <?= date('d-m-Y') ?></p>
					<br></br><br></br>
					<br></br>
					<p class='text-center'><strong><u>--------</u></strong></p>
					<p class='text-center'>Mandor QC</p>
			  </div>
			  <!-- /.col -->
			</div>
			<!-- /.row -->

		  </section>
		  <!-- /.content -->
		</div>
		<!-- ./wrapper -->

		<script type="text/javascript">
		  window.addEventListener("load", window.print());
		</script>
	</body>
</html>