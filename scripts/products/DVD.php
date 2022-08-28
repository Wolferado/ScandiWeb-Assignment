<?php
    // Including abstract Product class.
    require_once 'Product.php';

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
?>