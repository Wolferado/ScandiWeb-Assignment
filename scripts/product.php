<?php
    session_start();

    abstract class Product {
        public function &getSKU($args) {
            return $args['skuField'];
        }

        public function &getName($args) {
            return $args['nameField'];
        }

        public function getPrice($args) {
            return number_format((float)$args['priceField'], 2, '.', '')." $";
        }

        public function &getType($args) {
            return $args['productType'];
        }

        abstract function addToDatabase($args);

        abstract function displayProduct($row);
    }

    class DVD extends Product {
        function &getSize($args) {
            return $args['size'];
        }

        function addToDatabase($args) {
            $db = new DatabaseActivity();

            $sku = $this->getSKU($args);
            $name = $this->getName($args);
            $price = $this->getPrice($args);
            $type = $this->getType($args);
            $size = $this->getSize($args);

            $arr = array($sku, $name, $price, $type, $size);

            $query = 'INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_size) VALUES (?, ?, ?, ?, ?)';
            
            $db->addProductToDatabase($sku, $query, $arr);
        }

        function displayProduct($row)
        {
            // TODO: Create a function to display product without HTML code
            
            echo("<div class='product'>
            <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$row['product_sku']}>
            <span>{$row['product_sku']}</span>
            <span>{$row['product_name']}</span>
            <span>{$row['product_price']}</span>
            <span>{$row['product_size']} MB</span>
            </div>");
            
        }
    }

    class Book extends Product {
        function &getWeight($args) {
            return $args['weight'];
        }

        function addToDatabase($args) {
            $db = new DatabaseActivity();

            $sku = $this->getSKU($args);
            $name = $this->getName($args);
            $price = $this->getPrice($args);
            $type = $this->getType($args);
            $weight = $this->getWeight($args);

            $arr = array($sku, $name, $price, $type, $weight);

            $query = 'INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_weight) VALUES (?, ?, ?, ?, ?)';
            
            $db->addProductToDatabase($sku, $query, $arr);
        }

        function displayProduct($row)
        {
            
            echo("<div class='product'>
            <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$row['product_sku']}>
            <span>{$row['product_sku']}</span>
            <span>{$row['product_name']}</span>
            <span>{$row['product_price']}</span>
            <span>{$row['product_weight']} KG</span> 
            </div>");
            
        }
    }

    class Furniture extends Product {
        function &getHeight($args) {
            return $args['height'];
        }

        function &getWidth($args) {
            return $args['width'];
        }

        function &getLength($args) {
            return $args['length'];
        }

        function addToDatabase($args) {
            $db = new DatabaseActivity();

            $sku = $this->getSKU($args);
            $name = $this->getName($args);
            $price = $this->getPrice($args);
            $type = $this->getType($args);
            $height = $this->getHeight($args);
            $width = $this->getWidth($args);
            $length = $this->getLength($args);

            $arr = array($sku, $name, $price, $type, $height, $width, $length);

            $query = 'INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_height, product_width, product_length) VALUES (?, ?, ?, ?, ?, ?, ?)';
            
            $db->addProductToDatabase($sku, $query, $arr);
        }

        function displayProduct($row)
        {
            
            echo("<div class='product'>
            <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$row['product_sku']}>
            <span>{$row['product_sku']}</span>
            <span>{$row['product_name']}</span>
            <span>{$row['product_price']}</span>
            <span>{$row['product_height']}x{$row['product_width']}x{$row['product_length']} CM</span> 
            </div>");
            
        }
    }

    class DatabaseActivity {
        public function __construct()
        {
            try {
                $this->databaseHandle = new PDO('mysql:host=localhost;dbname=db_products', 'root', '');
            }
            catch (PDOException $e) {
                print("Error: " . $e->getMessage());
            }
        }

        function checkIfSkuIsTaken($sku) {
            $checkForSkuSql = $this->databaseHandle->prepare('SELECT * FROM tbl_products WHERE product_sku = ?');
            $checkForSkuSql->bindParam(1, $sku);
            $checkForSkuSql->execute();
            $result = $checkForSkuSql->fetchAll(PDO::FETCH_ASSOC);

            if($result) {
                $_SESSION['repeatableSKU'] = true;
                return true;
            }
            else 
                return false;
        }

        function addProductToDatabase($sku, $sqlRequest, $args) {
            if($this->checkIfSkuIsTaken($sku) == true) {
                header("Location: ../add-product.php");
                return;
            }

            $sql = $this->databaseHandle->prepare($sqlRequest);
            
            $length = count($args);

            for($i = 0; $i < $length; $i++) {
                $sql->bindParam((1 + $i), $args[$i]);
            }

            $sql->execute();
            header("Location: ../index.php");
        }

        function deleteProductFromDatabase() {
            if(isset($_POST['deleteCheckbox'])) {
                $products = $_POST['deleteCheckbox'];
        
                $sql = $this->databaseHandle->prepare('DELETE FROM tbl_products WHERE product_sku = ?');
        
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
        }

        function displayProductsFromDatabase() {
            $sql = $this->databaseHandle->prepare('SELECT * FROM tbl_products');

            try {
                $sql->execute();
                $result = $sql->fetchAll();

                foreach($result as $row) {
                    $productType = $row['product_type']; 

                    $product = new $productType();
                    $product->displayProduct($row);
                }
            }
            catch (PDOException $e) {
                echo($e->getMessage());
            }
        }
    }

    function addProduct() {
        // Variable for storing $_POST values.
        $args = array();

        // Saving $_POST keys and values.
        foreach($_POST as $key => $value) {
            if(!empty($key))
                $args[$key] = $value;
        }

        // Save product type from selected option.
        $productType = $args['productType']; 

        // Creating an object, based on the selected option from the list.
        $product = new $productType();
        $product->addToDatabase($args);
    }

    function deleteCheckedProducts() {
        $db = new DatabaseActivity();
        $db->deleteProductFromDatabase();
        header("Location: ../index.php");
    }

    function displayExistingProducts() {
        $db = new DatabaseActivity();
        $db->displayProductsFromDatabase();
    }

    if(basename($_SERVER["PHP_SELF"]) === "product.php" && !isset($_POST['submit']) && isset($_POST['nameField']))
        addProduct();
    else if(basename($_SERVER["PHP_SELF"]) === "product.php" && !isset($_POST['submit']) && !isset($_POST['nameField']))
        deleteCheckedProducts();
?>