<?php

    session_start();
    if( isset( $_SESSION['cart'] ) ){
        $cart = $_SESSION['cart'];
    }else{
        http_response_code(200);
        header('Content-Type: application/json');
        echo '{"products": [], "total": 0}';
        exit();
    }
    require('../DB/Connection.php');
    $products = [];

    foreach ( $cart as $cartItem){
        $productID = $cartItem->productid;
        $productquery = $mysqli->query("SELECT sku, name, price, shipping, image  FROM products WHERE sku=$productID ");
        $product = $productquery->fetch_assoc();
        $products[]  = (object) [ 'id'=> $product['sku'], 'name'=> $product['name'], 'price'=> $product['price'], 'shipping'=> $product['shipping'], 'image'=> $product['image'], 'qte'=>$cartItem->qte, 'Total'=>( ( (float)$product['price'] )*$cartItem->qte + ( (float) $product['shipping'] ) )  ];
    }

    $total = 0;
    $totalShippng = 0;
    foreach ($products as $product){
        $total += $product->Total;
        $totalShippng += $product->shipping;
    }

    $strProducts = json_encode($products);

    http_response_code(200);
    header('Content-Type: application/json');
    echo '{"products": '.$strProducts.', "total": '.$total.', "totalShipping": '.$totalShippng.'}';
    exit();