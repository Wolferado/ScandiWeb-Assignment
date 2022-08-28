<?php
    // Including DatabaseActivity class.
    require('./database.php');

    // Initialize DatabaseActivity class for further operations.
    $db = new DatabaseActivity();

    // Checks once form is submitted (necessary to call needed function based on the input of the form).
    if(!isset($_POST['submit']) && isset($_POST['nameField'])) // If form contains input for the name - form adds product to database.
        $db->addProductToDatabase();
    else if(!isset($_POST['submit']) && !isset($_POST['nameField'])) // Otherwise - form deletes checked products from database.
        $db->deleteCheckedProductsFromDatabase();
?>