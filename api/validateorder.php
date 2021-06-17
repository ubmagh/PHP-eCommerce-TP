<?php
    session_start();
    if( isset($_SESSION['user']) )
        $user = $_SESSION['user'];
    else{
        http_response_code(401);
        header('Content-Type: application/json');
        echo '{"status":"auth"}';
        exit();
    }

    if( isset($_SESSION["cart"]) )
        $cart = $_SESSION['cart'];
    else{
        http_response_code(400);
        header('Content-Type: application/json');
        echo '{"status":"emptyCart"}';
        exit();
    }

    require('../DB/Connection.php');

    $CommandeQuery  = $mysqli->query("INSERT INTO commandes(userid) values($user->id) ");
    $commandeID = $mysqli->insert_id;


    foreach ($cart as $product){
        $mysqli->query( "INSERT INTO lignecommande(commandeid, productid, Qte) values( $commandeID, $product->productid, $product->qte)");
    }

    unset($_SESSION['cart']);
    http_response_code(200);
    header('Content-Type: application/json');
    echo '{"status":"success"}';
    exit();

