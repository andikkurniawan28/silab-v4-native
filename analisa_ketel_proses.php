<?php
include('session_manager.php');

$sample_id = intval($_POST['sample_id']);

function valOrNull($val) {
    if ($val === '' || $val === null) return null;
    if (is_numeric($val) && floatval($val) == 0) return null;
    return floatval($val);
}

$pH     = valOrNull($_POST['pH'] ?? null);
$TDS    = valOrNull($_POST['TDS'] ?? null);
$Sadah  = valOrNull($_POST['Sadah'] ?? null);
$P2O5   = valOrNull($_POST['P2O5'] ?? null);
$Silika = valOrNull($_POST['Silika'] ?? null);

$sql = "
    UPDATE analisa_off_farm_new
    SET
        `pH`     = ".($pH !== null ? $pH : "NULL").",
        `TDS`    = ".($TDS !== null ? $TDS : "NULL").",
        `Sadah`  = ".($Sadah !== null ? $Sadah : "NULL").",
        `P2O5`   = ".($P2O5 !== null ? $P2O5 : "NULL").",
        `Silika` = ".($Silika !== null ? $Silika : "NULL")."
    WHERE id = $sample_id
";

$conn->query($sql);

$_SESSION['flash'] = [
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => 'Data berhasil disimpan'
];
header('Location: analisa_ketel_index.php');
exit;
