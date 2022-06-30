// Function to add functionality to ADD button.
(function addBtnFunctionality() {
    let addBtn = $("header button").eq(0);

    addBtn.on('click', function() {
        window.location.href = "add-product.php";
    });
})();

// Function to add functionality to MASS DELETE button.
(function massDeleteBtnFunctionality() {
    let massDeleteBtn = $("header button").eq(1);
    let form = $("section form").eq(0);

    massDeleteBtn.on('click', function() {
        let checkboxes = $("section form input[type=checkbox]:checked");

        if(checkboxes.length == 0) 
            alert('Please, select products to delete');
        else 
            form.submit();
    });
})();

// Function to adapt footer, based on the row count of product list.
(function footerAdaptiveness() {
    let products = $(".product");

    if(products.length > 8) {
        let rowCount = parseInt(products.length / 4);
        if(products.length % 4 != 0) {
            rowCount++;
        }
        console.log(rowCount);
        let gapSize = (rowCount - 3) * -200;
        console.log(gapSize);
        $("footer").css("bottom", gapSize);
    }
})();