<?php
    $title = "Boutique - Mes commandes ";
    require_once("./DB/Connection.php");

    session_start();
    if( (! isset($_SESSION['user']) ) )
        return header("Location: /index.php");
    else
        $user= $_SESSION['user'];

    require("./Parts/Header.php");


    $CommandsQuery = $mysqli->query("SELECT * FROM commandes WHERE userid=$user->id ORDER BY date desc");
    $commands=  $CommandsQuery->fetch_all(MYSQLI_ASSOC);
    unset($CommandsQuery);
    for (  $i=0; $i<count($commands); $i++){
        $commands[$i] = (object) $commands[$i];
        $LCQuery = $mysqli->query("SELECT * FROM lignecommande WHERE commandeid=".$commands[$i]->id." ");
        $LC =  $LCQuery->fetch_all(MYSQLI_ASSOC);
        $commands[$i]->products=[];
        $total = 0;
        foreach ($LC as $LC_item){
            $productsQuery = $mysqli->query("SELECT * FROM products WHERE sku=".$LC_item['productid']);
            $products = $productsQuery->fetch_all(MYSQLI_ASSOC);
            for ( $j=0; $j< count($products); $j++){
                $products[$j] = (object) $products[$j];
                $products[$j]->qte = $LC_item['Qte'];
                $products[$j]->Total =  ((float)$products[$j]->price) * ((int) $products[$j]->qte )  + ((int) $products[$j]->shipping );
                $products[$j]->Total = number_format((float)$products[$j]->Total, 2, '.', '') ;
                $commands[$i]->products[] =  $products[$j];
                $total += $products[$j]->Total;
            }
        }
        $commands[$i]->Total = $total;
    }
    // $commands=[]; //testing !
?>

<div class="content mb-5">
    <div class="container mb-5">
        <div class="card rounded-3 mb-4">
            <div class="card-header py-3">
                <h1 class="h1 fw-light text-center">
                    La liste des commandes :
                </h1>
            </div>
        </div>

        <?php if( count($commands) ){
            $numCommande = count($commands);
            foreach ( $commands as $command){
            ?>
                <div class="card my-4 rounded-3">
                    <div class="card-header">
                        <h4 class="h4 fw-normal text-center"> Commande numéro : <?= $numCommande-- ?>  <span class="text-secondary ms-2">( <?= substr( $command->date, 0, 11) ?> ) </span> </h4>
                    </div>
                    <div class="card-body bg-light px-2 py-3">
                        <table class="table bg-white table-hover ">
                            <thead>
                            <tr class="text-center">
                                <th class="text-start"> #</th>
                                <th class="text-start">Nom du produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Shipping</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($command->products as $product){ ?>
                                    <tr class="">
                                        <td scope="row"> <img src="<?= $product->image ?>" alt="image" style="width: 2em; height: 4em;"> </td>
                                        <td class="align-middle"> <a href="/product.php?product=<?= $product->sku ?>" target="_blank"> <?= $product->name ?> </a>  </td>
                                        <td class="text-center align-middle">
                                            <?= $product->qte ?>
                                        </td>
                                        <td class="text-center align-middle"> <?= $product->price ?> </td>
                                        <td class="text-center align-middle"> <?= $product->shipping ?> </td>
                                        <td class="text-center align-middle">  <?= $product->Total ?> </td>
                                    </tr>
                                <?php } ?>
                                <tr  >
                                    <td colspan="5" class="align-bottom "  style="height: 5em;">
                                        <h4 class="h5 fw-bold text-end me-2">
                                            Total à payer :
                                        </h4>
                                    </td>
                                    <td class="text-center align-bottom" style="height: 5em; border-top: black !important;">
                                        <h4 class="h5 fw-light text-center ">
                                            <?= $command->Total ?> MAD
                                        </h4>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


        <?php } }else{ ?>
            <div class="card rounded-3">
                <div class="card-body py-3">
                    <div class="alert alert-warning my-0" role="alert">
                        <h3 class="h3 fw-normal text-center">
                            <i class="fas fa-exclamation-circle"></i> Aucune commande n'a été trouvée !
                        </h3>
                    </div>
                </div>
            </div>
        <?php }?>
    </div>
</div>
<div style="height: <?= count($commands)*7?>vh;">
</div>

<?php
require("./Parts/Footer.php");
?>