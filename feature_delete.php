<?php
include('db.php');

$id = intval($_POST['id']);

$conn->query("
    DELETE FROM features WHERE id='$id'
");

echo 'ok';
