<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css">
    <title>Product List</title>
</head>
<body>
    <header>
        <h1>Product List</h1>
        <div id="btn-container">
            <button id="add-product-btn">ADD</button>
            <button id="delete-product-btn">MASS DELETE</button>
        </div>
    </header>
    <section id="product-list">
        <form method="POST" action="scripts/product.php">
            <?php
                include("scripts/product.php");
                displayExistingProducts();
            ?>
        </form>
    </section>
    <footer>
        Scandiweb Test assignment
    </footer>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="scripts/index.js"></script>