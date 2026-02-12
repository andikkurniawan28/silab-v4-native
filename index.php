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
    'Mandor On Farm', 
    'Analis On Farm', 
    'Operator Pabrikasi',
    'Staff Teknik',
    'Staff Tanaman',
    'Staff TUK',
    'Direksi',
    // 'Tamu',
    ]);
include('header_rev.php'); 

?>

<!-- VIEW -->
<div class="container-fluid">
    <h4 class="mb-3">Home</h4>
</div>

<?php include('footer.php'); ?>