<?php

    if( !isset($_GET['productid']) || !isset($_GET['operation'])  ){
        http_response_code(404);
        exit();
    }

    require_once('../DB/Connection.php');
    session_start();
    $productid = filter_var( $_GET['productid'],FILTER_SANITIZE_NUMBER_INT);
    $operation = filter_var( $_GET['operation'],FILTER_SANITIZE_NUMBER_INT);
    $operation = (boolean) $operation;

    $getproductQuery = $mysqli->query("SELECT * FROM products WHERE sku=$productid ");
    $product = $getproductQuery->fetch_assoc();

    if( empty( $product ) ){
        http_response_code(500);
        header('Content-Type: application/json');
        echo '{"status":"invalideData"}';
        exit();
    }

    if( isset($_SESSION['cart']) ){
        $cart = $_SESSION['cart'];
        $count = count($cart);
        for( $i=0; $i<$count; $i++)
            if( $cart[$i]->productid === $productid  ) {
                if($operation)
                    $cart[$i]->qte ++;
                else
                    $cart[$i]->qte --;
            }
    }else{
        http_response_code(500);
        header('Content-Type: application/json');
        echo '{"status":"notfound"}';
        exit();
    }

    $_SESSION['cart'] = $cart;

    http_response_code(200);
    header('Content-Type: application/json');
    echo '{"status":"success"}';
    exit();