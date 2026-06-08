<?php

$conn = mysqli_connect("localhost", "root", "", "silab");

$result = mysqli_query($conn, "
    SELECT id
    FROM analisa_on_farms
    WHERE TIME(ari_at) < '11:00:00'
");

while ($row = mysqli_fetch_assoc($result)) {

    $id = (int)$row['id'];

    do {

        // Rendemen random 3.00 - 8.00 (float)
        $rendemen = mt_rand(300, 800) / 100;

        // Brix random 10.0 - 25.0 (float)
        $brix = mt_rand(100, 250) / 10;

        // Invers rumus rendemen
        $pol = (($rendemen / 0.7) + (0.5 * $brix)) / 1.5;

    } while ($pol >= $brix);

    mysqli_query($conn, "
        UPDATE analisa_on_farms
        SET
            rendemen_ari = " . round($rendemen, 2) . ",
            brix_ari = " . round($brix, 2) . ",
            pol_ari = " . round($pol, 2) . "
        WHERE id = {$id}
    ");
}

echo "Selesai";