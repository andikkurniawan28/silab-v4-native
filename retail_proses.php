<?php
include('session_manager.php');

/* ---------- HELPER ---------- */
function generateTimestamp($date, $hour){
    $created_at = $date.' '.str_pad($hour,2,'0',STR_PAD_LEFT).":00:00";
    return date('Y-m-d H:i:s', strtotime($created_at.' +1 minute'));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $jam     = $_POST['jam'];
    $value   = $_POST['value'];

    // Nullable berat
    $berat_a = $_POST['berat_a'] !== '' ? $_POST['berat_a'] : null;
    $berat_b = $_POST['berat_b'] !== '' ? $_POST['berat_b'] : null;
    $berat_c = $_POST['berat_c'] !== '' ? $_POST['berat_c'] : null;
    $berat_d = $_POST['berat_d'] !== '' ? $_POST['berat_d'] : null;
    $berat_e = $_POST['berat_e'] !== '' ? $_POST['berat_e'] : null;
    $berat_f = $_POST['berat_f'] !== '' ? $_POST['berat_f'] : null;

    $created_at = generateTimestamp($tanggal, $jam);
    
    // Checkbox mesin aktif
    $mesin_aktif = isset($_POST['mesin_aktif']) ? $_POST['mesin_aktif'] : [];
    $mesin_aktif_json = json_encode($mesin_aktif);

    $expired_checked = isset($_POST['expired_checked']) ? 1 : 0;
    
    try {
        $stmt = $conn->prepare("
            INSERT INTO retail  
            (
                mesin_aktif,
                value,
                berat_a,
                berat_b,
                berat_c,
                berat_d,
                berat_e,
                berat_f,
                expired_checked,
                created_at
            ) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $mesin_aktif_json,
            $value,
            $berat_a,
            $berat_b,
            $berat_c,
            $berat_d,
            $berat_e,
            $berat_f,
            $expired_checked,
            $created_at
        ]);
        
        $_SESSION['flash'] = [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Data berhasil disimpan'
        ];
        header("Location: retail_index.php");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['flash'] = [
            'type'    => 'error',
            'title'   => 'Gagal simpan',
            'message' => $e->getMessage()
        ];
        header("Location: retail_index.php");
        exit();
    }
}
?>