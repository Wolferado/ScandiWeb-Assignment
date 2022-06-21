<?php
    session_start();

    abstract class Product {
        public $wrongSKU = false;

        public function &getSKU($args) {
            return $args['skuField'];
        }

        public function &getName($args) {
            return $args['nameField'];
        }

        public function &getPrice($args) {
            return $args['priceField'];
        }

        public function &getType($args) {
            return $args['productType'];
        }

        abstract function addToDatabase($args);
    }

    class DVD extends Product {
        function &getSize($args) {
            return $args['size'];
        }

        function addToDatabase($args) {
            try {
                $databaseHandle = new PDO('mysql:host=localhost;dbname=db_products', 'root', '');
            }
            catch (PDOException $e) {
                print("Error: " . $e->getMessage());
            }

            $sql = $databaseHandle->prepare('INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_size) VALUES (?, ?, ?, ?, ?)');
            $sql->bindParam(1, $this->getSKU($args));
            $sql->bindParam(2, $this->getName($args));
            $sql->bindParam(3, $this->getPrice($args));
            $sql->bindParam(4, $this->getType($args));
            $sql->bindParam(5, $this->getSize($args));

            try {
                $sql->execute();
                header("Location: ../index.php");
            }
            catch (PDOException $e) {
                if($e->getCode() == 23000) {
                    $_SESSION['repetableSKU'] = true;   
                    header("Location: ../add-product.php");
                }
                else
                    echo($e->getMessage());
            }
        }
    }

    class Book extends Product {
        function &getWeight($args) {
            return $args['weight'];
        }

        function addToDatabase($args) {
            try {
                $databaseHandle = new PDO('mysql:host=localhost;dbname=db_products', 'root', '');
            }
            catch (PDOException $e) {
                print("Error: " . $e->getMessage());
            }

            $sql = $databaseHandle->prepare('INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_weight) VALUES (?, ?, ?, ?, ?)');
            $sql->bindParam(1, $this->getSKU($args));
            $sql->bindParam(2, $this->getName($args));
            $sql->bindParam(3, $this->getPrice($args));
            $sql->bindParam(4, $this->getType($args));
            $sql->bindParam(5, $this->getWeight($args));

            try {
                $sql->execute();
                header("Location: ../index.php");
            }
            catch (PDOException $e) {
                if($e->getCode() == 23000) {
                    $_SESSION['repetableSKU'] = true;
                    header("Location: ../add-product.php");
                }
                else
                    echo($e->getMessage());
            }
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
            try {
                $databaseHandle = new PDO('mysql:host=localhost;dbname=db_products', 'root', '');
            }
            catch (PDOException $e) {
                print("Error: " . $e->getMessage());
            }

            $sql = $databaseHandle->prepare('INSERT INTO tbl_products (product_sku, product_name, product_price, product_type, product_height, product_width, product_length) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $sql->bindParam(1, $this->getSKU($args));
            $sql->bindParam(2, $this->getName($args));
            $sql->bindParam(3, $this->getPrice($args));
            $sql->bindParam(4, $this->getType($args));
            $sql->bindParam(5, $this->getHeight($args));
            $sql->bindParam(6, $this->getWidth($args));
            $sql->bindParam(7, $this->getLength($args));

            try {
                $sql->execute();
                header("Location: ../index.php");
            }
            catch (PDOException $e) {
                if($e->getCode() == 23000) {
                    $_SESSION['repetableSKU'] = true;
                    header("Location: ../add-product.php");
                }
                else
                    echo($e->getMessage());
            }
        }
    }

    function addProductToDatabase() {
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

    addProductToDatabase();
?>