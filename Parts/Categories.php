<?php
    // needed vars : $mysqli, $actual_link
    $CategoriesQuery = $mysqli->query("SELECT categories.id, categories.name, count(product_categories.product_id) as numProducts from categories, product_categories WHERE categories.id = product_categories.category_id GROUP BY product_categories.category_id ORDER BY categories.name");
    $categories = $CategoriesQuery->fetch_all(MYSQLI_ASSOC);
    foreach ($categories as $category){
?>
        <li class="list-group-item text-center"> <a href="/index.php<?php echo "?page=".$page; if(isset($search)) echo "&search=".$search; if($ordre!="asc")echo"&ordre=".$ordre; echo "&category=".$category["id"] ?>" class="text-decoration-none text-dark">  <?= $category["name"] ?> ( <?= $category["numProducts"] ?> )</a> </li>

<?php } ?>