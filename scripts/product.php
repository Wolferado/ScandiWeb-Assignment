<?php
    require('database.php');

    // Abstract (base) class for all products and their functions.
    abstract class Product {
        // Function to get SKU of the product.
        protected function &getSKU($args) {
            return $args['skuField'];
        }

        // Function to get name of the product.
        protected function &getName($args) {
            return $args['nameField'];
        }

        // Function to get formatted price of the product.
        protected function getPrice($args) {
            return $args['priceField'];
        }

        // Function to get type of the product.
        protected function &getType($args) {
            return $args['productType'];
        }

        // Abstract function for product to be displayed on product list.
        protected abstract function displayProduct($row);
    }

    // DVD Product class based on abstract Product class.
    class DVD extends Product {
        // Function to get size of the DVD.
        protected function &getSize($args) {
            return $args['size'];
        }

        // Function for DVD to be displayed on Product List.
        public function displayProduct($row)
        {
            $price = number_format((float)$row['product_price'], 2, '.', '');
            echo <<< PRODUCT
                <div class='product'>
                    <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$row['product_sku']}>
                    <span>{$row['product_sku']}</span>
                    <span>{$row['product_name']}</span>
                    <span>{$price} $</span>
                    <span>{$row['product_size']} MB</span>
                </div>
PRODUCT;
        }
    }

    // Book Product class based on abstract Product class.
    class Book extends Product {
        // Function to get weight of the book.
        protected function &getWeight($args) {
            return $args['weight'];
        }

        // Function for Book to be displayed on Product List.
        public function displayProduct($row)
        {
            $price = number_format((float)$row['product_price'], 2, '.', '');
            echo <<< PRODUCT
                <div class='product'>
                    <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$row['product_sku']}>
                    <span>{$row['product_sku']}</span>
                    <span>{$row['product_name']}</span>
                    <span>{$price} $</span>
                    <span>{$row['product_weight']} KG</span> 
                </div>
PRODUCT;
        }
    }

    // Furniture Product class based on abstract Product class.
    class Furniture extends Product {
        // Function to get Furniture height.
        protected function &getHeight($args) {
            return $args['height'];
        }

        // Function to get Furniture width.
        protected function &getWidth($args) {
            return $args['width'];
        }

        // Function to get Furniture length.
        protected function &getLength($args) {
            return $args['length'];
        }

        // Function for Furniture to be displayed on Product List.
        public function displayProduct($row)
        {
            $price = number_format((float)$row['product_price'], 2, '.', '');
            echo <<< PRODUCT
                <div class='product'>
                    <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$row['product_sku']}>
                    <span>{$row['product_sku']}</span>
                    <span>{$row['product_name']}</span>
                    <span>{$price} $</span>
                    <span>{$row['product_height']}x{$row['product_width']}x{$row['product_length']} CM</span> 
                </div>
PRODUCT;
        }
    }

    // Initialize DatabaseActivity class for further operations.
    $db = new DatabaseActivity();

    // Checks once form is submitted (necessary to call needed function based on the input of the form).
    if(basename($_SERVER["PHP_SELF"]) === "product.php" && !isset($_POST['submit']) && isset($_POST['nameField'])) // If form contains input for the name - form adds product to database.
        $db->addProductToDatabase();
    else if(basename($_SERVER["PHP_SELF"]) === "product.php" && !isset($_POST['submit']) && !isset($_POST['nameField'])) // Otherwise - form deletes checked products from database.
        $db->deleteCheckedProductsFromDatabase();
?>