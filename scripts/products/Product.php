<?php
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
?>