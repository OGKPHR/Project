function addNewOption(inputId, tableName) {
    var inputValue = document.getElementById(inputId).value.trim();
    if (inputValue !== "") {
        // Send the value to the server for insertion
        $.ajax({
            type: "POST",
            url: "add_option.php", // Create a PHP script to handle the addition
            
            data: { optionValue: inputValue, tableName: tableName },
            success: function(response) {
                // Optionally, update the select options dynamically here
                $('#' + inputId).val(''); // Clear the input field
                $('#' + inputId).closest('.modal').modal('hide');
                location.reload();
            }
        });
    }
}
function deleteSelectedOption(tableName) {
    var select = document.getElementById('Provider'); // Change this to match your select element's ID
    var selectedIndex = select.selectedIndex;
    if (selectedIndex >= 0) {
        var selectedOptionValue = select.options[selectedIndex].value;
        
        // Send the selected option value to the server for deletion
        $.ajax({
            type: "POST",
            url: "delete_option.php", // Create a PHP script to handle the deletion
            data: { optionValue: selectedOptionValue, tableName: tableName },
            success: function(response) {
                // Optionally, update the select options dynamically here
                select.options[selectedIndex].remove();
            }
        });
    }
}
function deleteSelectedOptionT(tableName) {
    var select = document.getElementById('TYPES'); // Change this to match your select element's ID
    var selectedIndex = select.selectedIndex;
    if (selectedIndex >= 0) {
        var selectedOptionValue = select.options[selectedIndex].value;
        
        // Send the selected option value to the server for deletion
        $.ajax({
            type: "POST",
            url: "delete_option.php", // Create a PHP script to handle the deletion
            data: { optionValue: selectedOptionValue, tableName: tableName },
            success: function(response) {
                // Optionally, update the select options dynamically here
                select.options[selectedIndex].remove();
            }
        });
    }
}