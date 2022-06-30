<?php
    session_start();

    // Abstract (base) class for all products and their functions.
    abstract class Product {
        // Function to get SKU of the product.
        public function &getSKU($args) {
            return $args['skuField'];
        }

        // Function to get name of the product.
        public function &getName($args) {
            return $args['nameField'];
        }

        // Function to get formatted price of the product.
        public function getPrice($args) {
            return number_format((float)$args['priceField'], 2, '.', '');
        }

        // Function to get type of the product.
        public function &getType($args) {
            return $args['productType'];
        }

        // Abstract function for product to be added to database.
        abstract function addToDatabase($args);

        // Abstract function for product to be displayed on product list.
        abstract function displayProduct($row);
    }

    // DVD Product class based on abstract Product class.
    class DVD extends Product {
        // Function to get size of the DVD.
        function &getSize($args) {
            return $args['size'];
        }

        // Function for DVD to be added to database.
        function addToDatabase($args) {
            // Adding object of DatabaseActivity class.
            $db = new DatabaseActivity();

            // Getting parameters of DVD.
            $sku = $this->getSKU($args);
            $name = $this->getName($args);
            $price = $this->getPrice($args);
            $type = $this->getType($args);
            $size = $this->getSize($args);

            // Creating array of parameters.
            $arr = array($sku, $name, $price, $type, $size);

            // Creating a SQL query.
            $query = 'INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_size) VALUES (?, ?, ?, ?, ?)';
            
            // Adding DVD via DatabaseActivity function.
            $db->addProductToDatabase($query, $arr);
        }

        // Function for DVD to be displayed on Product List.
        function displayProduct($row)
        {
            echo <<< PRODUCT
                <div class='product'>
                    <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$row['product_sku']}>
                    <span>{$row['product_sku']}</span>
                    <span>{$row['product_name']}</span>
                    <span>{$row['product_price']} $</span>
                    <span>{$row['product_size']} MB</span>
                </div>
            PRODUCT;
        }
    }

    // Book Product class based on abstract Product class.
    class Book extends Product {
        // Function to get weight of the book.
        function &getWeight($args) {
            return $args['weight'];
        }

        // Function for Book to be added to database.
        function addToDatabase($args) {
            // Adding object of DatabaseActivity class.
            $db = new DatabaseActivity();

            // Getting parameters of Book.
            $sku = $this->getSKU($args);
            $name = $this->getName($args);
            $price = $this->getPrice($args);
            $type = $this->getType($args);
            $weight = $this->getWeight($args);

            // Creating array of parameters.
            $arr = array($sku, $name, $price, $type, $weight);

            // Creating a SQL query.
            $query = 'INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_weight) VALUES (?, ?, ?, ?, ?)';
            
            // Adding Book via DatabaseActivity function.
            $db->addProductToDatabase($query, $arr);
        }

        // Function for Book to be displayed on Product List.
        function displayProduct($row)
        {
            echo <<<PRODUCT
                <div class='product'>
                    <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$row['product_sku']}>
                    <span>{$row['product_sku']}</span>
                    <span>{$row['product_name']}</span>
                    <span>{$row['product_price']} $</span>
                    <span>{$row['product_weight']} KG</span> 
                </div>
            PRODUCT;    
        }
    }

    // Furniture Product class based on abstract Product class.
    class Furniture extends Product {
        // Function to get Furniture height.
        function &getHeight($args) {
            return $args['height'];
        }

        // Function to get Furniture width.
        function &getWidth($args) {
            return $args['width'];
        }

        // Function to get Furniture length.
        function &getLength($args) {
            return $args['length'];
        }

        // Function for Furniture to be added to database.
        function addToDatabase($args) {
            // Adding object of DatabaseActivity class.
            $db = new DatabaseActivity();

            // Getting parameters of Furniture.
            $sku = $this->getSKU($args);
            $name = $this->getName($args);
            $price = $this->getPrice($args);
            $type = $this->getType($args);
            $height = $this->getHeight($args);
            $width = $this->getWidth($args);
            $length = $this->getLength($args);

            // Creating array of parameters.
            $arr = array($sku, $name, $price, $type, $height, $width, $length);

            // Creating SQL query.
            $query = 'INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_height, product_width, product_length) VALUES (?, ?, ?, ?, ?, ?, ?)';
            
            // Adding Furniture via DatabaseActivity function.
            $db->addProductToDatabase($query, $arr);
        }

        // Function for Furniture to be displayed on Product List.
        function displayProduct($row)
        {
            echo <<<PRODUCT
                <div class='product'>
                    <input class='delete-checkbox' type='checkbox' name='deleteCheckbox[]' value={$row['product_sku']}>
                    <span>{$row['product_sku']}</span>
                    <span>{$row['product_name']}</span>
                    <span>{$row['product_price']} $</span>
                    <span>{$row['product_height']}x{$row['product_width']}x{$row['product_length']} CM</span> 
                </div>
            PRODUCT;
        }
    }

    // Class for database functionality and activity.
    class DatabaseActivity {
        // Initializing database connection upon constructing.
        public function __construct()
        {
            try {
                $this->databaseHandle = new PDO('mysql:host=localhost;dbname=db_products', 'root', '');
            }
            catch (PDOException $e) {
                print("Error: " . $e->getMessage());
            }
        }

        // Function to check if database contains entered SKU.
        function checkIfSkuIsTaken($sku) {
            // Preparing and executing SQL query for getting product based on the SKU value.
            $checkForSkuSql = $this->databaseHandle->prepare('SELECT * FROM tbl_products WHERE product_sku = ?');
            $checkForSkuSql->bindParam(1, $sku);
            $checkForSkuSql->execute();

            // Fetching the result of the query.
            $result = $checkForSkuSql->fetchAll(PDO::FETCH_ASSOC);

            // Check if result contains any data and return boolean value.
            if($result) {
                $_SESSION['repeatableSKU'] = true;
                return true;
            }
            else 
                return false;
        }

        // Function to add entered product to database.
        function addProductToDatabase($sqlQuery, $args) {
            // Check if SKU is already taken or not.
            if($this->checkIfSkuIsTaken($args[0]) == true) {
                header("Location: ../add-product.php");
                return;
            }

            // Prepare SQL query.
            $sql = $this->databaseHandle->prepare($sqlQuery);
            
            // Get the amount of the parameters (for dynamic binding).
            $length = count($args);

            // Through the loop, set all necessary parameters.
            for($i = 0; $i < $length; $i++) {
                $sql->bindParam((1 + $i), $args[$i]);
            }

            // Execute SQL query and return to Product List.
            $sql->execute();
            header("Location: ../index.php");
        }

        // Function to delete selected products from database.
        function deleteProductFromDatabase() {
            // Check, if there are any checked checkboxes.
            if(isset($_POST['deleteCheckbox'])) {
                // Get all checked checkboxes' values (that are SKU values).
                $products = $_POST['deleteCheckbox'];
        
                // Prepare SQL query for deletion.
                $sql = $this->databaseHandle->prepare('DELETE FROM tbl_products WHERE product_sku = ?');
        
                // For each SKU acquired, execute a SQL query.
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

        // Function to display products from database.
        function displayProductsFromDatabase() {
            // Prepare SQL query for selection of all products and its data.
            $sql = $this->databaseHandle->prepare('SELECT * FROM tbl_products');

            // Executing SQL query and acquiring product type, after that calling product's display function, that is unique for each product.
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

    // Function to add the product from the Product Add page.
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

    // Function to delete checked products.
    function deleteCheckedProducts() {
        // Adding object from DatabaseActivity class.
        $db = new DatabaseActivity();

        // Calling its function to delete product and going back to Product List page.
        $db->deleteProductFromDatabase();
        header("Location: ../index.php");
    }

    // Function to display existing products.
    function displayExistingProducts() {
        // Adding object from DatabaseActivity class.
        $db = new DatabaseActivity();
        // Calling its function to display existing products.
        $db->displayProductsFromDatabase();
    }

    // Checks once form is submitted (necessary to call needed function based on the input of the form).
    if(basename($_SERVER["PHP_SELF"]) === "product.php" && !isset($_POST['submit']) && isset($_POST['nameField'])) // If form contains input for the name - call addProduct.
        addProduct();
    else if(basename($_SERVER["PHP_SELF"]) === "product.php" && !isset($_POST['submit']) && !isset($_POST['nameField'])) // Otherwise - call deleteCheckedProducts.
        deleteCheckedProducts();
?>