 function addOption() {
        var providerName = prompt("Enter provider name:");
        if (providerName !== null && providerName.trim() !== "") {
            var select = document.getElementById("Provider");
            var option = document.createElement("option");
            option.value = providerName;
            option.text = providerName;
            select.appendChild(option);
        }
    }

    function deleteOption() {
        var select = document.getElementById("Provider");
        var selectedValue = select.value;
        if (selectedValue !== "") {
            select.remove(select.selectedIndex);
        }
    }