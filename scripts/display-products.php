<?php
    abstract class ProductCard {
        abstract function __construct($row);

        public function &getSKU($row) {
            return $row['product_sku'];
        }

        public function &getName($row) {
            return $row['product_name'];
        }

        public function getPrice($row) {
            return number_format((float)$row['product_price'], 2, '.', '')." $";
        }
    }

    class DVDCard extends ProductCard {
        function __construct($row)
        {
            echo("<div class='product'>
            <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$this->getSKU($row)}>
            <span>{$this->getSku($row)}</span>
            <span>{$this->getName($row)}</span>
            <span>{$this->getPrice($row)}</span>
            <span>{$this->getSize($row)}</span>
            </div>");
        }

        function getSize($row) {
            return $row['product_size']." MB";
        }
    }

    class BookCard extends ProductCard {
        function __construct($row)
        {
            echo("<div class='product'>
            <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$this->getSKU($row)}>
            <span>{$this->getSku($row)}</span>
            <span>{$this->getName($row)}</span>
            <span>{$this->getPrice($row)}</span>
            <span>{$this->getWeight($row)}</span> 
            </div>");
        }

        function getWeight($row) {
            return $row['product_weight']." KG";
        }
    }

    class FurnitureCard extends ProductCard {
        function __construct($row)
        {
            echo("<div class='product'>
            <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$this->getSKU($row)}>
            <span>{$this->getSku($row)}</span>
            <span>{$this->getName($row)}</span>
            <span>{$this->getPrice($row)}</span>
            <span>{$this->getHeight($row)}x{$this->getWidth($row)}x{$this->getLength($row)} CM</span> 
            </div>");
        }

        function &getHeight($row) {
            return $row['product_height'];
        }

        function &getWidth($row) {
            return $row['product_width'];
        }

        function &getLength($row) {
            return $row['product_length'];
        }
    }

    // Function to add to product list product card (display it on the main page).
    function addProductCard() {
        try {
            $databaseHandle = new PDO('mysql:host=localhost;dbname=db_products', 'root', '');
        }
        catch (PDOException $e) {
            print("Error: " . $e->getMessage());
        }

        $sql = $databaseHandle->prepare('SELECT * FROM tbl_products');

        try {
            $sql->execute();
            $result = $sql->fetchAll();

            foreach($result as $row) {
                $productType = $row['product_type']."Card"; 

                new $productType($row);
            }
        }
        catch (PDOException $e) {
            echo($e->getMessage());
        }
    }

    // Function to delete checked product cards.
    function deleteProductCards() {
        if(isset($_POST['deleteCheckbox'])) {
            $products = $_POST['deleteCheckbox'];
            $productsSKU = implode(' ', $products);

            try {
                $databaseHandle = new PDO('mysql:host=localhost;dbname=db_products', 'root', '');
            }
            catch (PDOException $e) {
                print("Error: " . $e->getMessage());
            }
    
            $sql = $databaseHandle->prepare('DELETE FROM tbl_products WHERE product_sku = ?');
    
            try {
                foreach($products as $productSKU) {
                    $sql->bindParam(1, $productSKU);
                    $sql->execute();   
                }
            }
            catch (PDOException $e) {
                echo($e->getMessage());
            }
        }

        header("Location: ../index.php");
    }

    // If any checkboxes checked - delete products from database.
    if(isset($_POST['deleteCheckbox'])) {
        deleteProductCards();
    }
?>