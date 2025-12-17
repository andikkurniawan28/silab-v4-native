<?php
include('db.php');

$id = $_POST['id'];

$conn->query("
    DELETE FROM analisa_off_farm_new
    WHERE id='$id'
");
