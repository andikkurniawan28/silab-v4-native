<?php
session_start();
include('db.php');

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php?error=Silakan login terlebih dahulu");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head.php'); ?>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include('sidebar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column text-dark">
            <div id="content">
                <?php include('navbar.php'); ?>