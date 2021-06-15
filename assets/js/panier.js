//#CartModal
var Cartmodal = new bootstrap.Modal(document.getElementById('CartModal'), { keyboard: true })
let CartData = null;

function ShowCart(){
    // importer les produits du pannier (AJAX Query)
    $.get("/api/getcartproducts.php").done(data=>{
        CartData=data;
        // formatter et ajouter les produits dans la boite du dialogue ( $.append() )
        $('#cartTbody').empty();
        if( CartData.products.length ){
            CartData.products.forEach(product=>{
                $('#cartTbody').append(`  <tr class="">
                                                <td scope="row"> <img src="${product.image}" alt="image" style="width: 2em; height: 4em;"> </td>
                                                <td class="align-middle"> <a href="/product.php?product=${product.id}" target="_blank"> ${product.name} </a>  </td>
                                                <td class="text-center align-middle"> ${product.qte} </td>
                                                <td class="text-center align-middle"> ${product.price}</td>
                                                <td class="text-center align-middle"> ${product.shipping}</td>
                                                <td class="text-center align-middle"> ${product.Total}</td>
                                            </tr>   `);
            });
            $('#cartTbody').append(`<tr style="height: 5em;">
                                        <td scope="row" colspan="5" class="text-end align-bottom"> <h3 class="h5">Totale : </h3></td>
                                        <td scope="row"  class="text-center  align-bottom"> <h3 class="h5"> ${CartData.total}</h3> </td>
                                    </tr>`);
        }else{
            $('#cartTbody').append(`<tr style="height: 2em;">
                                        <td scope="row"  class="text-center middle" colspan="6"> <h3 class="h5 alert alert-warning">  Aucun produit dans le panier ! </h3> </td>
                                    </tr>
                                    <tr style="height: 5em;">
                                        <td scope="row" colspan="5" class="text-end align-bottom"> <h3 class="h5">Totale : </h3></td>
                                        <td scope="row"  class="text-center  align-bottom"> <h3 class="h5"> 0 </h3> </td>
                                    </tr>`);
        }
    });
    Cartmodal.show(); // afficher la boite
}

