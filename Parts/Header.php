<?php

echo "";?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title?></title>
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/assets/css/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="/assets/css/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/all.min.js"></script>
</head>
<body>
<div class="pageHeader">
    <nav class="navbar topnavbar navbar-dark container-fluid py-3">
        <div class="container text-center">
            <a class="navbar-brand mx-auto " href="/index.php"> <h1 class="h1  fw-light"> <i class="fas fa-store"></i> Boutique </h1> </a>
        </div>
    </nav>
    <?php require("./Parts/Pannier.php"); ?>
</div>
