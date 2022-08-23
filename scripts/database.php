<?php
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
        private function checkIfSkuIsTaken($sku) {
            // Starting session for storing boolean value regarding repeatable SKU.
            session_start();
            
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
        public function addProductToDatabase() {
            // Variable for storing $_POST values.
            $args = array();
    
            // Saving $_POST keys and values.
            foreach($_POST as $key => $value) {
                if(!empty($key))
                    $args[$key] = $value;
            }

            // Check if SKU is already taken or not.
            if($this->checkIfSkuIsTaken($args['skuField']) == true) {
                header("Location: ../add-product.php");
                return;
            }

            // Getting all necessary fields.
            $sku = $args['skuField'];
            $name = $args['nameField'];
            $price = $args['priceField'];
            $type = $args['productType'];
            $size = $args['size'];
            $weight = $args['weight'];
            $height = $args['height'];
            $width = $args['width'];
            $length = $args['length'];

            // Creating and preparing SQL query.
            $query = 'INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_size, product_weight, product_height, product_width, product_length) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $sql = $this->databaseHandle->prepare($query);

            // Binding params to the SQL query.
            $sql->bindParam(1, $sku);
            $sql->bindParam(2, $name);
            $sql->bindParam(3, $price);
            $sql->bindParam(4, $type);
            $sql->bindParam(5, $size);
            $sql->bindParam(6, $weight);
            $sql->bindParam(7, $height);
            $sql->bindParam(8, $width);
            $sql->bindParam(9, $length);

            // Execute the query and return to the home page.
            $sql->execute();
            header("Location: ../index.php");
        }


        // Function to display products from database.
        public function displayProductsFromDatabase() {
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

        // Function to delete selected products from database.
        public function deleteCheckedProductsFromDatabase() {
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

                // Return to the home page.
                header("Location: ../index.php");
            }
        }
    }
?>