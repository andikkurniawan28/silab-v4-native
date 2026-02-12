
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

$id = intval($_GET['id']);

$conn->query("DELETE FROM retail WHERE id=$id");

$_SESSION['flash'] = [
    'type'=>'success',
    'title'=>'Berhasil',
    'message'=>'Data berhasil dihapus'
];

header('Location: retail_index.php');
exit;
