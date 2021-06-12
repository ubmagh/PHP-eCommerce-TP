<?php

$dbuser="root";//set it to visitor user later on !!!!!!
$dbpass="";
$host="localhost";
$db="boutique";
$mysqli =  new mysqli($host,$dbuser,$dbpass,$db);//new mysqli($host,$dbuser,$dbpass,$db);  or  mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if ( $mysqli->connect_error ) { // $mysqli->connect_error or !$mysqli

    echo '
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Database connection Error !</title>
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="alert alert-danger mx-3">
    <strong> Error !</strong>  Database not Connected !
    </div>
</body>
    ';
    exit();
}

?>