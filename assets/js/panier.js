//#CartModal
var Cartmodal = new bootstrap.Modal(document.getElementById('CartModal'), { keyboard: true })
let CartData = null;

document.getElementById('CartModal').addEventListener('hidden.bs.modal', function (event) {
    $('.table').removeClass("d-none");
    $('.Message').addClass("d-none");
})

async function GetCartProducts(){
    await $.get("/api/getcartproducts.php").done(data=> {
        CartData = data;
    });
}

async function deleteProductFromCart(productid){
    const fd = new FormData();
    fd.append('productid',productid);

    await fetch( "/api/deleteproductfromcart.php",{
        method: 'post',
        body: fd,
    }).then(async res=>{
        if( res.status!=200 )
            alert("une erreur servenue ! Veuilliez rafraichir cette page .")
        await GetCartProducts();
    }).then(()=>renderRows());
}

function renderRows(){
    $('#cartTbody').empty();
    if( CartData.products.length ){
        CartData.products.forEach(product=>{
                                            $('#cartTbody').append(`  <tr>
                                                <td scope="row"> <img src="${product.image}" alt="image" style="width: 2em; height: 4em;"> </td>
                                                <td class="align-middle"> <a href="/product.php?product=${product.id}" target="_blank"> ${product.name} </a>  </td>
                                                <td class="text-center align-middle"> 
                                                    <span class="d-inline-block customBtn" onclick="ChangeQte( event, ${product.id}, false)"><i class="fas fa-minus-circle "></i></span>
                                                    <span class="mx-2 value">
                                                        ${product.qte}
                                                    </span> 
                                                    <span class="d-inline-block customBtn" onclick="ChangeQte( event, ${product.id}, true)"><i class="fas fa-plus-circle "></i></span>
                                                </td>
                                                <td class="text-center align-middle"> ${product.price}</td>
                                                <td class="text-center align-middle"> ${product.shipping}</td>
                                                <td class="text-center align-middle"> ${product.Total}</td>
                                                <td class="text-center align-middle"> <button type="button" onclick="deleteProductFromCart(${product.id})" class="btn btn-danger"> <i class="fa fa-trash" ></i> </button> </td>
                                            </tr>   `);
        });
        $('#cartTbody').append(`    <tr style="height: 5em;">
                                        <td colspan="5" class="text-end align-bottom"> <h3 class="h5">Totale : </h3></td>
                                        <td class="text-center  align-bottom"> <h3 class="h5"> ${CartData.total} MAD</h3> </td>
                                        <td></td>
                                    </tr>`);
    }else{
        $('#cartTbody').append(`    <tr style="height: 2em;">
                                        <td scope="row"  class="text-center middle h-100" colspan="7"> <h3 class="h5 alert alert-warning m-0">  Aucun produit dans le panier ! </h3> </td>
                                    </tr>
                                    <tr style="height: 5em;">
                                        <td scope="row" colspan="5" class="text-end align-bottom"> <h3 class="h5">Totale : </h3></td>
                                        <td scope="row" colspan="2"  class="text-center  align-bottom"> <h3 class="h5"> 0 MAD</h3> </td>
                                    </tr>`);
    }

    if( CartData.products.length )
        $('.validate').removeClass('d-none');
    else
        $('.validate').addClass('d-none');

}

function ChangeQte( event, productid, bool_Inc_Dec){
    $('.customBtn').addClass('text-light');
    const clickedObject = $(event.target).closest('.customBtn')[0];
    $.get("/api/changequantity.php",{productid:productid, operation: ( bool_Inc_Dec? 1:0 )} ).done( async ()=>{
        if( bool_Inc_Dec )
            $(clickedObject).next().text( parseInt( $(clickedObject).next().text().trim() ) +1 );
        else
            $(clickedObject).previous().text( parseInt( $(clickedObject).prev().text().trim() ) -1 );
        await GetCartProducts();
        renderRows();
    });
}

function ClearCart(){
    $.get("/api/clearcart.php").always(async ()=>{
        await GetCartProducts();
        $('#pannelCount').text(' 0 ');
        renderRows();
    });
}

async function ShowCart(){
    // importer les produits du pannier (AJAX Query)
    await GetCartProducts();
    renderRows();
    Cartmodal.show(); // afficher la boite
}

function ValiderCommande(){
    $.get("/api/validateorder.php").done(res=>{
        if(res.status=="success"){
            $('.Message').empty().append(`
                 <div class="alert alert-success py-3 px-2 text-center align-center" role="alert">
                	<h3 class="h3 fw-normal text-center"> <i class="fas fa-check"></i> Commande Bien enregistrée ! </h3>
                 </div>
            `);
            $('.table').addClass("d-none");
            $('.validate').addClass("d-none");
            $('.Message').removeClass("d-none");
            $('#pannelCount').text(' 0 ');
        }
    }).fail(err=>{
        $('.Message').empty();
        let message = `
                 <div class="alert alert-danger py-3 px-2 text-center align-center" role="alert">
                	<h3 class="h3 fw-normal text-center"> <i class="fas fa-times"></i> Une erreur Servenue ! </h3>
                 </div>
        `;
        if( err.responseJSON.status=="emptyCart" )
            message = `
                 <div class="alert alert-warning py-3 px-2 text-center align-center" role="alert">
                	<h3 class="h3 fw-normal text-center"> <i class="fas fa-times"></i> Votre panier est vide ! </h3>
                 </div>
            `;
        if( err.responseJSON.status=="auth" )
            message= `
                <div class="alert alert-info py-3 px-2 text-center align-center" role="alert">
                    <h3 class="alert-heading fw-normal text-center"> Vous devez vous-connecter pour valider votre commande ! </h3>
                    <p></p>
                    <div class="py-3 row col-10 col-md-8 mx-auto mb-2 text-center">
                        <a class="btn btn-primary col-auto mx-auto px-4" href="/register.php" role="button"> <i class="fas fa-user-plus"></i> Créer votre compte </a>
                        <a class="btn btn-primary col-auto mx-auto px-4" href="/login.php" role="button"> <i class="fas fa-sign-in-alt"></i> se connecter </a>
                    </div>
                </div>
            `;
        $('.Message').append(message);
        $('.validate').addClass("d-none");
        $('.table').addClass("d-none");
        $('.Message').removeClass("d-none");
    });
}

