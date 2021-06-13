<?php

    $title = "Boutique - Acceuil";
    require_once("./DB/Connection.php");
    require("./Parts/Header.php");

    $page = 1;
    if( isset($_GET["page"]) ){
        $page = filter_var( $_GET["page"],FILTER_SANITIZE_NUMBER_INT );
        $page = (int) $page;
    }
    else
        $page = 1;

    if( (!$page) || (!is_int($page)) )
        $page= 1;

    if( isset($_GET['category']) ) {
        $category = filter_var($_GET['category'], FILTER_SANITIZE_STRING);
    }

    if( isset($_GET["ordre"])){
        $ordre = filter_var( $_GET["ordre"],FILTER_SANITIZE_STRING );
        $ordre = (($ordre=="asc")? "asc":"desc");
    }else
        $ordre = "asc";

    if( isset($_GET['search']))
        $search= filter_var($_GET['search'], FILTER_SANITIZE_STRING);

    if( isset($search) && $search!==false )
        $countQuery = "SELECT count(sku) as num FROM products WHERE name like '%$search%' ";
    else
        $countQuery = "SELECT count(sku) as num FROM products ";

    if( isset($category) ){
        if( isset($search) )
            $countQuery .= "AND sku in (SELECT product_id FROM product_categories WHERE category_id='$category' ) ";
        else
            $countQuery .= "WHERE sku in (SELECT product_id FROM product_categories WHERE category_id='$category' ) ";
    }

    $countQuery = $mysqli->query($countQuery);
    $countProducts = $countQuery->fetch_assoc();
    $countProducts = $countProducts['num'];
    $elementsPerPage = 10;
    $numberOfPages = ceil( $countProducts / $elementsPerPage );
    if($page>$numberOfPages)
        $page=$numberOfPages;
    if( $page<1)
        $page=1;

    $offset = ($page-1)*$elementsPerPage;

    if( isset( $category ) ) {
        $CategoryQuery = "SELECT * FROM categories WHERE id='$category' ";
        $CategoryQuery = $mysqli->query($CategoryQuery);
        $categoryDetails = $CategoryQuery->fetch_assoc();
    }



    $selectProductsQuery = "SELECT sku, name, price, description, image FROM products ";

    if( isset($search) && $search!==false )
        $selectProductsQuery .= "WHERE name like '%$search%' ";

    if( isset($category) ){
        if(isset($search))
            $selectProductsQuery .= "AND sku in (SELECT product_id FROM product_categories WHERE category_id='$category' ) ";
        else
            $selectProductsQuery .= "WHERE sku in (SELECT product_id FROM product_categories WHERE category_id='$category' ) ";
    }


    if( isset($ordre) )
        $selectProductsQuery .= "ORDER BY price $ordre ";

    $selectProductsQuery .= "LIMIT $elementsPerPage ";
    if( $offset )
        $selectProductsQuery .= "OFFSET $offset ";

    $selectProductsQuery = $mysqli->query($selectProductsQuery);

    $products = $selectProductsQuery->fetch_all(MYSQLI_ASSOC);
    // $products=[]; // test no product found

    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

?>
<div class="content">
    <div class="container mb-5">
        <div class="d-flex flex-column-reverse flex-md-row my-3">
            <div class="col-12 col-md-8 pe-md-1">
                <div class="card bg-light rounded-2 py-3 mb-3">
                    <form action="<?= $actual_link ?>" method="get" class=" col-11 mx-auto">
                        <h2 class="h2 display-6 fw-light ps-2 pt-2"> Chercher un produit : </h2>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control form-control-lg border-dark" aria-label="product name" aria-describedby="srchBtn" value="<?= isset($search)? $search:NULL ?>" name="search" placeholder="nom du produit">
                            <button class="btn btn-outline-dark" type="submit" id="srchBtn"> <i class="fas fa-search fa-lg"></i> </button>
                        </div>
                        <?php if( isset($category) )?>
                        <input type="hidden" name="category" value="<?= $category ?>">
                    </form>
                    <div class="mt-4 mb-2 text-end col-11 mx-auto">
                        <h4 class="h5 fw-normal text-secondary d-inline"> Trier par le prix : </h4>
                        <input type="radio" name="ordre"  value="asc" class="ui-checkboxradio ui-helper-hidden-accessible" <?=  $ordre=="asc"? 'checked="checked"':NULL ?>>
                        <label for="radio1" onclick="setOrder('asc')" class="ui-checkboxradio-label ui-corner-all ui-button ui-widget ui-checkboxradio-radio-label <?=  $ordre=="asc"? 'ui-checkboxradio-checked ui-state-active':NULL ?>">
                            <i class="fas fa-chevron-down fa-lg"></i>
                            Croissant
                        </label>

                        <input type="radio" name="ordre" value="desc" class="ui-checkboxradio ui-helper-hidden-accessible" <?=  $ordre!="asc"? 'checked="checked"':NULL ?> >
                        <label for="radio2" onclick="setOrder('desc')" class="ui-checkboxradio-label ui-corner-all ui-button ui-widget ui-checkboxradio-radio-label <?=  $ordre!="asc"? 'ui-checkboxradio-checked ui-state-active':NULL ?>">
                            <i class="fas fa-chevron-up fa-lg"></i>
                            Decroissant
                        </label>
                    </div>

                </div>

                <div class="card bg-light rounded-3 pt-3 pb-2 mb-5">
                    <div class="card-body px-4">
                        <?php
                            if( isset($search) ) {
                        ?>
                            <div class="row mt-2 mb-3 py-2 border border-secondary rounded-2 show">
                                <div class="col">
                                    <h5 class="h5 float-start pt-1"> <span class="text-muted fw-normal">Résultats de la recherche :</span> '  <?= $search ?> '</h5>
                                </div>
                                <div class="col">
                                    <a class="btn btn-outline-danger float-end" href="/index.php?<?php if( isset($category) ) echo "category=".$category ?>" > <i class="fas fa-times"></i> </a>
                                </div>
                            </div>
                        <?php
                            } if(isset($category)){?>

                            <div class="row mt-2 mb-3 py-2 border border-secondary rounded-2 show">
                                <div class="col">
                                    <h5 class="h5  float-start pt-1"> <span class="text-muted fw-normal"> Catégorie selectionnée : </span>  <?= $categoryDetails['name'] ?> </h5>
                                </div>
                                <div class="col-auto ms-auto">
                                    <a class="btn btn-outline-danger float-end" href="\index.php<?php echo "?page=".$page; if(isset($search)) echo "&search=".$search; if( $ordre!="asc" ) echo "&ordre=".$ordre;  ?>" > <i class="fas fa-times"></i> </a>
                                </div>
                            </div>

                        <?php

                            }
                            if( count($products) ){
                                foreach ($products as $product){
                                    $product['description']= substr( $product['description'], 0, 150)."..." ;
                                    ?>

                                    <div class="row mb-2">
                                        <div class="col">
                                            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-3 shadow-product position-relative">
                                                <div class="col-auto d-flex">
                                                    <img src="<?= $product['image'] ?>" class="img-thumbnail thumbnail rounded-2 align-self-center" width="200" height="250" title="<?= $product['name'] ?>"  alt="<?= $product['name'] ?>">
                                                </div>
                                                <div class="col p-4 d-flex flex-column position-static align-content-center justify-content-center">
                                                    <div class="align-self-center">
                                                        <h2 class="mb-0 h2  text-break"><?= $product['name'] ?></h2>
                                                        <p class="mb-auto h6 mt-3 fw-normal text-secondary"> <?= $product['description'] ?> </p>
                                                    </div>
                                                    <div class="row w-100 bottom-0 align-self-end pb-0 mb-0 pt-4">
                                                        <div class="col text-start me-auto">
                                                            <h3 class="h3 text-info"><?= str_replace( '.', ',', $product['price']  ) ?> MAD</h3>
                                                        </div>
                                                        <div class="col ms-auto text-end">
                                                            <a href="/product.php?product=<?= $product['sku'] ?>" class="stretched-link text-decoration-none text-primary"><button id="button" class="ui-button ui-corner-all ui-widget"> <i class="fas fa-shopping-cart"></i> Acheter </button>  </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                }
                            }else{
                        ?>
                            <div class="alert alert-warning  py-3" role="alert">
                                <h4 class="h4 fw-normal text-center">
                                    <i class="fas fa-exclamation-circle fa-lg"></i> Aucun produit n'a été trouvé !
                                </h4>
                            </div>
                            <?php }?>
                    </div>
                    <?php if( $numberOfPages-1 ){ ?>
                        <div class="card-footer bg-light pt-3 mt-2 text-center">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <?php
                                        if( $page==1 ){
                                    ?>
                                        <li class="page-item disabled">
                                            <a class="page-link" disabled href="#" tabindex="-1" aria-disabled="true"> << Première page</a>
                                        </li>
                                        <li class="page-item disabled">
                                            <a class="page-link" disabled href="#" tabindex="-1" aria-disabled="true"> < Page précédente </a>
                                        </li>
                                    <?php
                                        }else{
                                    ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo "{$_SERVER['PHP_SELF']}?page=1"; if(isset($search)) echo "&search=".$search; if( $ordre!="asc" ) echo "&ordre=desc"; if( isset($category) ) echo "&category=".$category; ?>" tabindex="-1" > << Première page</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo "{$_SERVER['PHP_SELF']}?page=".($page-1); if(isset($search)) echo "&search=".$search; if( $ordre!="asc" ) echo "&ordre=desc"; if( isset($category) ) echo "&category=".$category; ?>" tabindex="-1" > < Page précédente </a>
                                        </li>
                                    <?php
                                        }
                                    ?>
                                    <li class="page-item  active">
                                        <a class="page-link disabled px-3" disabled >
                                            <?= $page." / ".$numberOfPages ?>
                                        </a>
                                    </li>
                                    <?php
                                        if( $page==$numberOfPages ){
                                    ?>
                                        <li class="page-item disabled">
                                            <a class="page-link" disabled href="#" tabindex="-1" aria-disabled="true"> Page suivante > </a>
                                        </li>
                                        <li class="page-item disabled">
                                            <a class="page-link" disabled href="#" tabindex="-1" aria-disabled="true">  Dernière page >> </a>
                                        </li>
                                    <?php
                                        }else{
                                    ?>
                                            <li class="page-item ">
                                                <a class="page-link"  href="<?php echo "{$_SERVER['PHP_SELF']}?page=".($page+1); if(isset($search)) echo "&search=".$search; if( $ordre!="asc" ) echo "&ordre=desc"; if( isset($category) ) echo "&category=".$category; ?>" tabindex="-1" > Page suivante > </a>
                                            </li>
                                            <li class="page-item ">
                                                <a class="page-link"  href="<?php echo "{$_SERVER['PHP_SELF']}?page=".$numberOfPages; if(isset($search)) echo "&search=".$search; if( $ordre!="asc" ) echo "&ordre=desc"; if( isset($category) ) echo "&category=".$category; ?>" tabindex="-1">  Dernière page >> </a>
                                            </li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </nav>


                        </div>
                    <?php } ?>
                </div>


            </div>
            <div class="col-12 col-md-4 ps-md-1 mt-3 mt-md-0">
                <div class="card bg-light rounded-2">
                    <div class="card-header py-3">
                        <h3 class="h4 text-center text-dark fw-light"> Catégories : </h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group border-0 m-0">
                            <?php require('./Parts/Categories.php') ?>
                        </ul>
                    </div>
                </div>
                <div class="card bg-light rounded-2 mt-2">
                    <div class="card-header pb-2">
                        <h3 class="h5 text-center fw-light pt-2"> publicité : </h3>
                    </div>
                    <div class="card-body p-1 text-center">
                        <img src="https://via.placeholder.com/400x200">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

    <script>
        function setOrder(val){
            const path = window.location.protocol + "//"+ window.location.host  + window.location.pathname;
            if( window.location.search){
                var search = ( (window.location.search).split('?') )[1];
                var pairs = search.split('&');
                let bol = false;
                pairs.forEach( (c,i)=>{
                    if( pairs[i].search("ordre") !=-1 ){
                        pairs[i]= "ordre="+val;
                        bol=!0;
                    }
                })
                if(!bol) pairs.push("ordre="+val);
                search = pairs.join('&');
                window.location = (path+"?"+search);
            }else{
                window.location = (path+"?ordre="+val);
            }

        }
    </script>
<?php
    require("./Parts/Footer.php");
?>