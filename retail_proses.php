<?php
include('session_manager.php');

/* ---------- HELPER ---------- */
function generateTimestamp($date, $hour){
    $created_at = $date.' '.str_pad($hour,2,'0',STR_PAD_LEFT).":00:00";
    return date('Y-m-d H:i:s', strtotime($created_at.' +1 minute'));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    // $user_id = $_POST['user_id'];
    $value = $_POST['value'];

    $created_at = generateTimestamp($tanggal, $jam);
    
    // Mengambil nilai dari checkbox yang dicentang
    $mesin_aktif = isset($_POST['mesin_aktif']) ? $_POST['mesin_aktif'] : [];
    
    // Convert array ke JSON untuk disimpan di database
    $mesin_aktif_json = json_encode($mesin_aktif);
    
    try {
        $stmt = $conn->prepare("INSERT INTO retail  
                              (mesin_aktif, value, created_at) 
                              VALUES (?, ?, ?)");
        
        $stmt->execute([$mesin_aktif_json, $value, $created_at]);
        
        $_SESSION['flash'] = [
            'type'=>'success',
            'title'=>'Berhasil',
            'message'=>'Data berhasil disimpan'
        ];
        header("Location: retail_index.php");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['flash'] = [
            'type'=>'error',
            'title'=>'Gagal simpan',
            'message'=>$e->getMessage()
        ];
        // $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
        header("Location: retail_index.php");
        exit();
    }
}
?>