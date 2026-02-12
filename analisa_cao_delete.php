<?php 

include('session_manager.php'); 
checkRoleAccess([
    'Superadmin', 
    'Kabag', 
    'Kasie', 
    'Kasubsie', 
    'Admin QC', 
    'Koordinator QC', 
    'Mandor Off Farm', 
    'Analis Off Farm', 
    // 'Mandor On Farm', 
    // 'Analis On Farm', 
    // 'Operator Pabrikasi',
    // 'Staff Teknik',
    // 'Staff Tanaman',
    // 'Staff TUK',
    // 'Direksi',
    // 'Tamu',
    ]);

?>

$id = intval($_GET['id']);

$conn->query("
    UPDATE analisa_off_farm_new
    SET CaO = NULL
    WHERE id = $id
");

$_SESSION['success'] = 'Analisa CaO berhasil dihapus';
header('Location: analisa_cao_index.php');
exit;
