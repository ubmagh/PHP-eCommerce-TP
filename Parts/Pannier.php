<?php
if( !isset($user) )
    session_start();
if( isset($_SESSION['cart']) )
    $cart = $_SESSION['cart'];
else
    $cart = [];

if(isset($_SESSION['user']))
    $user = $_SESSION['user'];
?>

<div class="container mb-5 mt-0">
    <div class="row pb-5 ">
        <div class="text-end">
            <?php if( isset($user) ){ ?>
                <a class="btn dropdown-toggle btn btn-outline-light px-5 py-3 me-2" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user "></i> <span class="mx-2"> <?= $user->name ?> </span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li><a class="dropdown-item px-4 py-3" href="/orders.php"> <i class="fas fa-receipt me-2"></i> Mes commandes </a></li>
                    <li><a class="dropdown-item px-4 py-3" href="/logout.php"> <i class="fas fa-power-off me-2"></i> Deconnexion </a></li>
                </ul>
            <?php }else{ ?>
                <a href="/login.php" class="btn btn-outline-light px-4 py-3 me-2">
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </a>
            <?php } ?>
            <button type="button" onclick="ShowCart()" class="btn btn-outline-light px-4 py-3">
                <i class="fas fa-shopping-cart"></i> Panier ( <span id="pannelCount"><?= count($cart) ?></span> )
            </button>
        </div>
    </div>
    <div class="modal fade rounded-3" id="CartModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> <i class="fas fa-shopping-cart text-secondary me-2"></i> Votre pannier </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <table class="table table-hover ">
                        <thead>
                        <tr class="text-center">
                            <th class="text-start">  #</th>
                            <th class="text-start">Nom du produit</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Shipping</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="cartTbody" >
                            <tr>
                                <td scope="row"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="Message d-none">
                    </div>
                </div>
                <div class="modal-footer text-center py-3 justify-content-center align-content-center d-none validate">
                    <button type="button" class="btn btn-success align-center " onclick="ValiderCommande()"> <i class="far fa-credit-card fa-lg"></i> Valider cette commande </button>
                    <button type="button" class="btn btn-danger align-center" onclick="ClearCart()"> <i class="fas fa-times fa-lg"></i> Vider mon panier </button>
                </div>
            </div>
        </div>
    </div>
</div>
