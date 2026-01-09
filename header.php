<?php
session_start();
include('session_manager.php');
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
                <div class="container-fluid mt-2">
                    <div class="alert alert-warning text-center fw-bold fs-4 shadow-sm" role="alert">
                        🚧 APLIKASI DALAM TAHAP <span class="badge bg-danger fs-5">BETA</span>  
                        <div class="fs-6 fw-normal mt-1">
                            Beberapa fitur mungkin belum stabil
                        </div>
                    </div>
                </div>