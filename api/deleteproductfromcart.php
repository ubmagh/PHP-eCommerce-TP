<?php
    if( !isset($_POST['productid'])  ){
        http_response_code(404);
        exit();
    }

    require_once('../DB/Connection.php');
    session_start();
    $productid = filter_var( $_POST['productid'],FILTER_SANITIZE_NUMBER_INT);

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
                for ($j = $i; $j < $count - 1; $j++)
                    $cart[$j] = $cart[$j + 1];
                unset( $cart[$count-1]);
                break;
            }
    }else{
        http_response_code(500);
        header('Content-Type: application/json');
        echo '{"status":"emptyCart"}';
        exit();
    }

    $_SESSION['cart'] = $cart;

    http_response_code(200);
    header('Content-Type: application/json');
    echo '{"status":"success"}';
    exit();