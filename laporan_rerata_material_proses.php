<?php

include('session_manager.php');
include('db.php');

$material_id = intval($_POST['material_id']);
$from = $_POST['from'];
$to = $_POST['to'];

/*
Range:
06:00 → 05:59 besok
*/

$start = $from . ' 06:00:00';

$end =
    date(
        'Y-m-d 05:59:59',
        strtotime($to . ' +1 day')
    );

/*
Ambil indicator milik material
*/

$indicatorQuery = mysqli_query(
    $conn,
    "
SELECT DISTINCT
indicators.name

FROM methods

JOIN indicators
ON indicators.id =
methods.indicator_id

WHERE methods.material_id =
$material_id

ORDER BY indicators.id
"
);

$avgColumns = [];

while (
    $row =
    mysqli_fetch_assoc(
        $indicatorQuery
    )
) {

    /*
    indicator:
    Pol Ampas

    kolom:
    Pol_Ampas
    */

    $label =
        $row['name'];

    $column =
        str_replace(
            ' ',
            '_',
            $label
        );

    $avgColumns[] =
        "
ROUND(
AVG(`$column`),
4
)
AS `$label`
";

}

if (empty($avgColumns)) {
    die("Tidak ada indicator");
}

$avgSql =
    implode(
        ",",
        $avgColumns
    );

/*
Ambil nama material
*/

$materialQuery =
    mysqli_query(
        $conn,
        "
SELECT name
FROM materials
WHERE id=$material_id
"
    );

$material =
    mysqli_fetch_assoc(
        $materialQuery
    );

$sql = "

SELECT

CASE

WHEN
TIME(created_at)
<'06:00:00'

THEN
DATE(
DATE_SUB(
created_at,
INTERVAL 1 DAY
)
)

ELSE
DATE(created_at)

END

AS tanggal,

COUNT(*) total,

$avgSql

FROM
analisa_off_farm_new

WHERE

material_id=
$material_id

AND created_at
BETWEEN
'$start'
AND
'$end'

GROUP BY tanggal

ORDER BY tanggal

";

$result =
    mysqli_query(
        $conn,
        $sql
    );

    

if($_POST['handling'] == 'export'){
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=Laporan_Rerata_Material_' . $_POST["date"] . '.xls');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Rerata Material
    </title>

    <link rel="icon"
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
            background: #f4f6f9;
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

                Laporan Rerata Material

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

                <?= htmlspecialchars($material['name']) ?>

            </span>

        </div>


        <!-- CARD -->

        <div class="card shadow-sm">

            <div class="card-body">

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

                                    Material

                                </th>

                                <td>

                                    <?= htmlspecialchars($material['name']) ?>

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
                        Rata-rata Material
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

                                <?php
                                foreach (
                                    mysqli_fetch_fields(
                                        $result
                                    )
                                    as
                                    $field
                                ):
                                ?>

                                    <th>

                                        <?= htmlspecialchars(
                                            $field->name
                                        ) ?>

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
                                mysqli_num_rows(
                                    $result
                                ) == 0
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
"
                                        class="
text-center
text-muted
py-4
">

                                        <i class="bi bi-inbox"></i>

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
                                                    is_numeric(
                                                        $value
                                                    )
                                                    &&
                                                    $key != 'total'
                                                ) {

                                                    echo
                                                    number_format(
                                                        $value,
                                                        2,
                                                        ',',
                                                        '.'
                                                    );
                                                } else {

                                                    echo
                                                    htmlspecialchars(
                                                        (string)$value
                                                    );
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


    <script
        src="
https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js
">
    </script>

</body>

</html>