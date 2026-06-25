<?php

include('session_manager.php');
include('db.php');

$from = $_POST['from'];
$to   = $_POST['to'];

$start = $from . ' 06:00:00';

$end = date(
    'Y-m-d 05:59:59',
    strtotime($to . ' +1 day')
);

/*
==================================
AMBIL LIST CHEMICAL
==================================
*/

$chemicals = [];

$res = $conn->query("
    SELECT id,name
    FROM chemicals
    ORDER BY id
");

$columns = [];

while ($row = $res->fetch_assoc()) {

    $id = $row['id'];

    $chemicals[] = $row;

    /*
    p1,p2,p3...
    */

    $columns[] = "
    ROUND(
        SUM(
            CASE
            WHEN p$id <> 0
            THEN p$id
            ELSE 0
            END
        ),
        2
    ) AS `{$row['name']}`
    ";
}

if (!$columns) {
    die('Chemical tidak ditemukan');
}

$dynamicColumn =
    implode(",", $columns);

/*
==================================
QUERY REKAP HARIAN
==================================
*/

$sql = "

SELECT

CASE

WHEN TIME(created_at) < '06:00:00'

THEN DATE(
    DATE_SUB(
        created_at,
        INTERVAL 1 DAY
    )
)

ELSE DATE(created_at)

END AS tanggal,

COUNT(*) total,

$dynamicColumn

FROM penggunaan_bpp

WHERE created_at
BETWEEN
'$start'
AND
'$end'

GROUP BY tanggal

ORDER BY tanggal

";

$result =
    $conn->query($sql);


if ($_POST['handling'] == 'export') {

    header(
        'Content-type: application/vnd-ms-excel'
    );

    header(
        'Content-Disposition: attachment; filename=Laporan_Rekap_BPP_Proses.xls'
    );
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Rekap BPP
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

    <div class="container-fluid py-4">

        <div class="card shadow">

            <div class="card-body">

                <h4>
                    Laporan Rekap BPP
                </h4>

                <div class="mb-3">

                    Periode:

                    <b>

                        <?= date(
                            'd/m/Y',
                            strtotime($from)
                        ) ?>

                        —

                        <?= date(
                            'd/m/Y',
                            strtotime($to)
                        ) ?>

                    </b>

                    <br>

                    <small>

                        Cutoff:
                        06:00 → 05:59

                    </small>

                </div>

                <div class="table-responsive">

                    <table
                        class="
table
table-bordered
table-striped
table-sm
">

                        <thead>

                            <tr>

                                <?php
                                foreach (
                                    mysqli_fetch_fields($result)
                                    as
                                    $field
                                ):
                                ?>

                                    <th>

                                        <?= $field->name ?>

                                    </th>

                                <?php endforeach; ?>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            mysqli_data_seek(
                                $result,
                                0
                            );

                            if (
                                mysqli_num_rows($result)
                                == 0
                            ):

                            ?>

                                <tr>

                                    <td
                                        colspan="
<?= count(
                                    mysqli_fetch_fields(
                                        $result
                                    )
                                ) ?>
">

                                        Tidak ada data

                                    </td>

                                </tr>

                                <?php
                            else:

                                while (
                                    $row =
                                    mysqli_fetch_assoc(
                                        $result
                                    )
                                ):
                                ?>

                                    <tr>

                                        <?php
                                        foreach (
                                            $row
                                            as
                                            $key
                                            =>
                                            $value
                                        ):
                                        ?>

                                            <td>

                                                <?php

                                                if (
                                                    is_numeric($value)
                                                    &&
                                                    $key != 'total'
                                                ) {

                                                    echo number_format(
                                                        $value,
                                                        2,
                                                        ',',
                                                        '.'
                                                    );
                                                } else {

                                                    echo $value;
                                                }

                                                ?>

                                            </td>

                                        <?php endforeach; ?>

                                    </tr>

                            <?php
                                endwhile;
                            endif;
                            ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>

</html>