// Function to change product section, when selected from the list (avoiding conditional statements as a requirement).
(function dynamicForm() {
    let typeSwitcher = $("#productType");

    typeSwitcher.on('change', function() {
        let fieldName = typeSwitcher.val() + "-info";
        let infos = $("fieldset");
        infos.hide();
        let neededInfo = $("#" + fieldName);
        neededInfo.css("display", "flex");
    });
})();

// Function to give SAVE button functionality to submit the form.
(function saveBtnFunctionality() {
    let saveBtn = $("header button").eq(0);
    let form = $("form").eq(0);
    
    saveBtn.on('click', function() {
        checkFormFields(form);
    });
})();

// Function to give CANCEL button functionality to return to product list.
(function cancelBtnFunctionality() {
    let cancelBtn = $("header button").eq(1);

    cancelBtn.on('click', function() {
        window.location.href = "index.php";
    });
})();

// Function to check, if form fields have correct input (not negative or zero and not empty).
function checkFormFields(form) {
    let inputs = $("input:visible");
    let warning = $("#input-warning");
    for(let i = 0; i < inputs.length; i++) {
        switch(i) {
            case 2:
            case 3:
            case 4:
            case 5:
                if(parseFloat(inputs.eq(i).val()) <= 0) {
                    warning.text("Please, provide the data of indicated type");
                    warning.show();
                    return;
                }
                break;  
        }

        if(inputs.eq(i).val().length == 0) {
            warning.text("Please, submit required data");
            if(warning.is(":hidden"))
                warning.slideToggle();
            return;
        }
    }

    form.submit();
}