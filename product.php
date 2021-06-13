<?php
    require_once("./DB/Connection.php");
    if( isset($_GET['product']) ){
        $product = filter_var( $_GET['product'], FILTER_SANITIZE_NUMBER_INT);
        $GetProductQuery = $mysqli->query("SELECT * FROM products WHERE sku=$product ");
        $product = $GetProductQuery->fetch_assoc();
        if( !isset($product['sku']) ) {
            $product = false;
            $title = "Boutique - Produit introuvable ";
        }
        else
            $title = "Boutique - ".$product['name'];
    }else {
        $product = false;
        $title = "Boutique - Produit introuvable ";
    }

require("./Parts/Header.php");
?>

<div class="content">
    <div class="container mb-5">
        <div class="row mt-3">
            <div class="col-auto me-auto">
                <button class="btn btn-lg btn-primary" onclick="prevPage()" role="button"><i class="fa fa-list fa-lg"></i> Retourner vers la liste des produits </button>
            </div>
        </div>
        <div class="row my-3">
            <div class="card rounded-3 bg-light py-2 px-3">
                <?php if( $product ){ ?>
                        <div class="col">
                            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-3 shadow-product position-relative px-3">
                                <div class="col-auto d-flex">
                                    <img src="<?= $product['image'] ?>" class="img-thumbnail thumbnail rounded-2 align-self-center" style="min-height: 500px;" title="<?= $product['name'] ?>"  alt="<?= $product['name'] ?>">
                                </div>
                                <div class="col p-4 d-flex flex-column position-static ">
                                    <h2 class="mb-0 h2  text-break"><?= $product['name'] ?></h2>
                                    <p class="h6 mt-3 fw-normal text-secondary"> <?= $product['description'] ?> </p>
                                    <div class="row px-2 px-md-4 px-lg-5 d-block">
                                        <?php if($product["type"]) ?>
                                        <p class="h6 mt-3 fw-normal text-secondary"> <span class="fw-bold">Type : </span><?= $product['type'] ?> </p>
                                        <?php if($product["upc"]) ?>
                                        <p class=" h6 mt-3 fw-normal text-secondary"> <span class="fw-bold">UPC : </span><?= $product['upc'] ?> </p>
                                        <?php if($product["manufacturer"]) ?>
                                        <p class=" h6 mt-3 fw-normal text-secondary"> <span class="fw-bold">Fabricant : </span><?= $product['manufacturer'] ?> </p>
                                        <?php if($product["model"]) ?>
                                        <p class=" h6 mt-3 fw-normal text-secondary"> <span class="fw-bold">Model : </span><?= $product['model'] ?> </p>
                                        <?php if($product["url"]) ?>
                                        <p class=" h6 mt-3 fw-normal text-secondary"> <a href="<?= $product['url'] ?>" class="text-decoration-none" target="_blank"> <i class="fas fa-external-link-alt"></i> Voir plus d'informations</a> </p>
                                    </div>

                                    <div class="row w-100 d-flex bottom-0 align-self-end pb-0 mb-0 pt-0" style="margin-top: -20px;">
                                        <div class="col-auto ms-auto text-center">
                                            <small class="text-muted text-start me-auto ms-0"> prix unitaire : </small>
                                            <div class="rounded-2 px-3 pt-3 pb-0 border" style="border-style: dashed !important; border-width: 3px !important;">
                                                <h3 class="h3 text-muted fw-normal"> Shipping : <span class="text-success fw-bold"><?= str_replace( '.', ',', $product['shipping']  ) ?> MAD</span></h3>
                                                <hr>
                                                <h3 class="h3 text-muted fw-normal"> Prix : <span class="text-success fw-bold"><?= str_replace( '.', ',', $product['price']  ) ?> MAD</span></h3>
                                            </div>
                                            <form id="form">
                                                <div class="pt-0 my-1 form-group ">
                                                    <input type="number" min="1" value="1" max="100" class="form-control form-control-lg text-center fw-bolder display-6" name="qte" aria-describedby="helpId" placeholder="Quantité">
                                                </div>
                                                <input type="hidden" name="productid" value="<?= $product['sku'] ?>">
                                                <button type="submit" id="" class="btn btn-warning btn-lg btn-block w-100"> <i class="fas fa-shopping-cart"></i> Ajouter au panier</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }else{?>
                    <div class="alert alert-warning py-5 my-0 " role="alert">
                        <h3 class="h3 display-5 fw-normal text-center"><i class="fas fa-exclamation-circle"></i> Produit est introuvable ! </h3>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Ajout au pannier </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-none" id="err">
                        <div class="alert alert-warning py-3 my-0" role="alert">
                            <h2 class="h3 text-center fw-normal"> <i class="fas fa-exclamation-circle"></i> Erreur dans les données envoyées ! </h2>
                        </div>
                    </div>
                    <div class="d-none" id="succ">
                        <div class="alert alert-success py-3 my-0" role="alert">
                            <h2 class="h3 text-center fw-normal"> <i class="fas fa-check"></i> Produit Bien ajouté à votre panier ! </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var modal = new bootstrap.Modal(document.getElementById('modal'), {
        keyboard: false
    })
    document.getElementById('modal').addEventListener('hidden.bs.modal', function (event) {
        $("#succ").addClass("d-none");
        $("#err").addClass("d-none");
    });
    function prevPage(){window.history.back()}
    $('#form').submit(e=>{
        e.preventDefault();
        // Ajax Here !!
        const data = $('#form').serialize();
        $.ajax({
            url: '/api/addproducttocart.php',
            type: 'POST',
            data: data,
            success: function(resp) {
                $('#succ').removeClass('d-none');
                $('#err').addClass('d-none');
                modal.show();
                if(resp.increment)
                    $("#pannelCount").text( parseInt($("#pannelCount").text()) +1 );
            },
            error : function(resp){
                $('#err').removeClass('d-none');
                $('#succ').addClass('d-none');
                modal.show();
            }
        });
    })
</script>


<?php
    require("./Parts/Footer.php");
?>
