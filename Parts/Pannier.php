<?php
session_start();
if( isset($_SESSION['cart']) )
    $cart = $_SESSION['cart'];
else
    $cart = [];
?>

<div class="container mb-5 mt-0">
    <div class="row pb-5 ">
        <div class="text-end">
            <h4 class="h3">
                <button type="button" onclick="ShowCart()" class="btn btn-outline-light px-4 py-3">
                    <i class="fas fa-shopping-cart"></i> Panier ( <span id="pannelCount"><?= count($cart) ?></span> )
                </button>
            </h4>
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
