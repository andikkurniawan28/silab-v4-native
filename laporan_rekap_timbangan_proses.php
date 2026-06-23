<?php

include('session_manager.php');

$type = $_POST['type'];
$from = $_POST['from'];
$to = $_POST['to'];

require_once(
    $type == 'tetes'
    ? 'db.php'
    : 'db_packer.php'
);

$allowed = [

    'rs_in' => [
        'table' => 'weighing_test',
        'column' => 'value',
        'where' => ''
    ],

    'rs_out' => [
        'table' => 'in_process_weighings',
        'column' => 'netto',
        'where' => "AND line='pringkilan'"
    ],

    'reject' => [
        'table' => 'in_process_weighings',
        'column' => 'netto',
        'where' => "AND line='rs_out'"
    ],

    'tetes' => [
        'table' => 'mollases',
        'column' => 'netto',
        'where' => ''
    ]

];

if (
    !isset(
        $allowed[$type]
    )
) {
    die('Invalid type');
}

$config =
    $allowed[$type];

$start =
    new DateTime(
        $from
    );

$end =
    new DateTime(
        $to
    );

$data = [];

while ($start <= $end) {

    $cutoffStart =
        $start->format('Y-m-d')
        . ' 06:00:00';

    $cutoffEnd =
        date(
            'Y-m-d 05:59:59',
            strtotime(
                $cutoffStart . ' +1 day'
            )
        );

    $cutoffStart =
        mysqli_real_escape_string(
            $conn,
            $cutoffStart
        );

    $cutoffEnd =
        mysqli_real_escape_string(
            $conn,
            $cutoffEnd
        );

    $sql = "

    SELECT
        SUM({$config['column']}) AS total

    FROM {$config['table']}

    WHERE
        created_at >= '$cutoffStart'
        AND created_at <= '$cutoffEnd'

    {$config['where']}

    ";

    $res =
        mysqli_query(
            $conn,
            $sql
        );

    if (!$res) {

        die(mysqli_error($conn)
            .
            '<br><br>'
            .
            $sql);
    }

    // FETCH SATU KALI SAJA
    $row =
        mysqli_fetch_assoc(
            $res
        );

    $data[] = [

        'tanggal' =>
        $start->format(
            'Y-m-d'
        ),

        'total' =>
        isset($row['total'])
            ? (float)$row['total']
            : 0

    ];

    $start->modify(
        '+1 day'
    );
}

if (
    $_POST['handling']
    ==
    'export'
) {

    header(
        'Content-type: application/vnd-ms-excel'
    );

    header(
        'Content-Disposition: attachment; filename=' . $type . '.xls'
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Rekap Timbangan
    </title>

    <link
        rel="icon"
        type="image/png"
        href="/silab-v4/admin_template/img/QC.png" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .card {
            border-radius: .75rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .text-xs {
            font-size: .75rem;
        }
    </style>

</head>

<body>

    <div class="container-fluid py-4">

        <!-- HEADER -->

        <div
            class="
d-flex
justify-content-between
align-items-center
mb-3
">

            <h5
                class="
mb-0
fw-semibold
">

                <i class="bi bi-bar-chart"></i>

                Laporan Rekap Timbangan

            </h5>

            <span
                class="
badge
bg-warning
text-dark
fs-6
px-3
py-2
">

                <?= strtoupper($type) ?>

            </span>

        </div>


        <!-- CARD -->

        <div class="card shadow-sm">

            <div class="card-body">

                <!-- INFO -->

                <div class="row mb-3">

                    <div class="col-md-12">

                        <table
                            class="
table
table-borderless
table-sm
">

                            <tr>

                                <th width="180">

                                    Jenis

                                </th>

                                <td>

                                    <?= strtoupper($type) ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Periode

                                </th>

                                <td>

                                    <?= date(
                                        'd/m/Y',
                                        strtotime($from)
                                    ) ?>

                                    —

                                    <?= date(
                                        'd/m/Y',
                                        strtotime($to)
                                    ) ?>

                                    <br>

                                    <small class="text-muted">

                                        Cutoff:
                                        06:00 → 05:59

                                    </small>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>


                <!-- TABEL -->

                <div class="table-responsive">

                    <h6>

                        Data Rekap

                    </h6>

                    <table
                        class="
table
table-dark
table-striped
table-sm
table-bordered
text-xs
">

                        <thead>

                            <tr>

                                <th>

                                    Tanggal

                                </th>

                                <th>

                                    Total

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (empty($data)): ?>

                                <tr>

                                    <td
                                        colspan="2"
                                        class="
text-center
text-muted
py-4
">

                                        <i class="bi bi-inbox"></i>

                                        Tidak ada data

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php $no = 1; ?>

                                <?php foreach ($data as $row): ?>

                                    <tr>

                                        <td>

                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $row['tanggal']
                                                )
                                            ) ?>

                                        </td>

                                        <td>

                                            <?= number_format(
                                                $row['total'],
                                                0,
                                                ',',
                                                '.'
                                            ) ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <script
        src="
https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js
">
    </script>

</body>

</html>