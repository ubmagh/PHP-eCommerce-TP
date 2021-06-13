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
            <h4 class="h3"><a class="btn btn-outline-light px-4 py-3" href="/pannier.php" role="button"> <i class="fas fa-shopping-cart"></i> panier ( <span id="pannelCount"><?= count($cart) ?></span> ) </a></h4>
        </div>
    </div>
</div>
