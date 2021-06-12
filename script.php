<?php
require("./DB/Connection.php");
$jsonFile = file_get_contents("./products.json");
if( $jsonFile ){
    $jsonArray = json_decode($jsonFile);
    unset($jsonFile);
    ini_set('max_execution_time', 3600);
    if( $mysqli->query("DELETE FROM products WHERE sku!=0") )// wipe table
        echo "Tous les enregistrements des <strong>Produits</strong> sont supprimés <br>";
    else
        echo "Les enregistrements de la table des<strong>Produits</strong> ne sont pas supprimés, Des erreurs d'insertion peuvent servenir ! <br>";
    if( $mysqli->query("DELETE FROM categories WHERE id!=0") )// wipe table
        echo "Tous les enregistrements des <strong>Categories</strong> sont supprimés <br>";
    else
        echo "Les enregistrements de la table des<strong>Categories</strong> ne sont pas supprimés, Des erreurs d'insertion peuvent servenir ! <br>";

    $productsQuery = $mysqli->prepare("INSERT INTO products VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?)");
    $productsQuery->bind_param( "issdsdsssss", $sku, $name, $type, $price, $upc, $shipping, $description, $manufacturer, $model, $url, $image);

    $categoriesQuery = $mysqli->prepare("INSERT IGNORE INTO categories VALUES (?, ?)");
    $categoriesQuery->bind_param( "ss", $cat_id, $cat_name);

    $ProductCatsQuery = $mysqli->prepare("INSERT IGNORE INTO product_categories VALUES (?, ?)");
    $ProductCatsQuery->bind_param( "is", $product_id, $category_id);
    $rows = 200;
    foreach ( $jsonArray as $product ){
        if(!$rows) break;
        $rows --;
        $sku = $product->sku;
        $name = $product->name;
        $type = $product->type;
        $price = $product->price;
        $upc = $product->upc;
        $shipping = $product->shipping;
        $description = $product->description;
        $manufacturer = $product->manufacturer?$product->manufacturer:NULL;
        $model = $product->model?$product->model:NULL;
        $url = $product->url;
        $image = $product->image;
        $productsQuery->execute();
        $product_id = $sku;
        foreach ( $product->category as $category){
            $category_id = $category->id;
            $cat_id= $category->id;
            $cat_name= $category->name;
            $categoriesQuery->execute();
            $ProductCatsQuery->execute();
        }
    }
    $categoriesQuery->close();
    $ProductCatsQuery->close();
    $productsQuery->close();
    echo " 200 produits sont ajoutés avec succès + leurs catégories + affectation de chaque produit à certaines catégories  !";
}else{
    //error opening the file
    echo "Fichier 'products.json' est introuvable !";
    exit();
}