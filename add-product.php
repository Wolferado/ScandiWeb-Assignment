<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/add-product.css">
    <title>Product Add</title>
</head>
<body>
    <header>
        <h1>Product Add</h1>
        <div id="btn-container">
            <button>Save</button>
            <button>Cancel</button>
        </div>
    </header>
    <section>
        <form id="product_form" method="post" action="scripts/product.php">
            <label for="sku">SKU</label>
            <input id="sku" name="skuField" type="text" autocomplete="off"> 
            <label for="name">Name</label>
            <input id="name" name="nameField" type="text" autocomplete="off"> 
            <label for="price">Price ($)</label>
            <input id="price" name="priceField" type="number" min="0.01" value="0.01"> 

            <label for="productType">Type Switcher</label>
            <select id="productType" name="productType">
                <option value="DVD">DVD</option>
                <option value="Book">Book</option>
                <option value="Furniture">Furniture</option>
            </select>

            <fieldset id="DVD-info">
                <label for="size">Size (MB)</label>
                <input id="size" name="size" type="number" min="1" step="1" value="1">
                <span>Please, provide size</span>
            </fieldset>

            <fieldset id="Book-info">
                <label for="weight">Weight (KG)</label>
                <input id="weight" name="weight" type="number" min="0" value="0.01">
                <span>Please, provide weight</span>
            </fieldset>

            <fieldset id="Furniture-info">
                <label for="height">Height (CM)</label>
                <input id="height" name="height" type="number" min="0" value="0.01">
                <label for="width">Width (CM)</label>
                <input id="width" name="width" type="number" min="0" value="0.01">
                <label for="length">Length (CM)</label>
                <input id="length" name="length" type="number" min="0" value="0.01">
                <span>Please, provide dimensions</span>
            </fieldset>

            <span id="input-warning">Sample Text</span>

            <?php
                // Using session variable to check, if SKU that got entered is already taken.
                if(empty($_SESSION['repetableSKU'])) {
                    $_SESSION['repetableSKU'] = false;
                }

                if($_SESSION['repetableSKU'] == true) {
                    echo("<span>SKU is taken, try another");
                    $_SESSION['repetableSKU'] = false;
                }
            ?>
        </form>
    </section>
    <footer>
        Scandiweb Test assignment
    </footer>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="scripts/add-product.js"></script>