<?php
    if( !isset($_POST['productid']) || !isset($_POST['qte']) ){
        http_response_code(404);
        exit();
    }

    require_once('../DB/Connection.php');
    session_start();
    $productid = filter_var( $_POST['productid'],FILTER_SANITIZE_NUMBER_INT);
    $qte = filter_var( $_POST['qte'],FILTER_SANITIZE_NUMBER_INT);
    $qte = (int) $qte;

    if( !is_int($qte) || $qte<0 || $qte>100 ){
        http_response_code(500);
        header('Content-Type: application/json');
        echo '{"status":"invalideData"}';
        exit();
    }

    $getproductQuery = $mysqli->query("SELECT * FROM products WHERE sku=$productid ");
    $product = $getproductQuery->fetch_assoc();

    if( empty( $product ) ){
        http_response_code(500);
        header('Content-Type: application/json');
        echo '{"status":"invalideData"}';
        exit();
    }

    $added =false;
    $increment = !0;
    if( isset($_SESSION['cart']) ){
        $cart = $_SESSION['cart'];
        foreach ( $cart as $cartitem){
            if( $cartitem->productid === $productid  ){
                $cartitem->qte += $qte;
                $added = !0;
                $increment= !1;
            }
        }
    }else
        $cart = [];

    if( !$added )
        $cart[] = (object) [ "productid"=>$productid, "qte"=>$qte ];

    $_SESSION['cart'] = $cart;

    http_response_code(200);
    header('Content-Type: application/json');
    echo '{"status":"success", "increment":'.($increment? 'true':'false').'}';
    exit();
?>
