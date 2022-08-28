<?php
    // Including abstract Product class.
    require_once 'Product.php';

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
?>