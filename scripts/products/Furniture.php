<?php
    // Including abstract Product class.
    require_once 'Product.php';
    
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
?>