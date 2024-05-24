<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/vtl.css">
    <title>Vtl_Generator_CreateForeignKey</title>
</head>
<body>
<h2 class="text-center">Vtl Data Generator: Create Foreign Key</h2>
<section class="container">
    <div>
        <div>
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
        </div>
        <p>Select tables from the dropdowns below to view their columns:</p>
</section>
<section class="container">

    <div class="dropdown-container">
        <div class="dropdown-wrapper">
            <h4>Foreign Key side</h4>
            <?php
            $tableChoiceAttr1['id'] = 'tableChoiceDropdown1';
            $tableChoiceAttr1['onchange'] = 'selectedTable(1)';
            echo form_dropdown('tableChoice1', $tables, '', $tableChoiceAttr1);
            ?>
            <div id="columnInfoTableContainer1" class="column-container"></div>
        </div>
        <div class="dropdown-wrapper">
            <h4>Related To side</h4>
            <?php
            $tableChoiceAttr2['id'] = 'tableChoiceDropdown2';
            $tableChoiceAttr2['onchange'] = 'selectedTable(2)';
            echo form_dropdown('tableChoice2', $tables, '', $tableChoiceAttr2);
            ?>
            <div id="columnInfoTableContainer2" class="column-container"></div>
        </div>
    </div>
    <div class="button-container">
        <button id="createForeignKeyBtn" onclick="createForeignKey()" style="display: none;">Create Foreign Key</button>
    </div>
</section>
<div class="modal" id="response-modal" style="display: none">
    <div class="modal-heading">Generated Rows</div>
    <div class="modal-body">
        <p id="the-response"></p>
        <p class="text-center">
            <button onclick="closeModal()" class="alt">Close</button>
        </p>
    </div>
</div>
<script src="<?= BASE_URL ?>js/app.js"></script>
</body>
</html>
<script>
    async function selectedTable(dropdownNumber) {
        // Get the dropdown element based on the dropdownNumber
        var dropdownId = 'tableChoiceDropdown' + dropdownNumber;
        var dropdown = document.getElementById(dropdownId);

        // Get the selected value
        var selectedTable = dropdown.options[dropdown.selectedIndex].text;

        // Find the columnInfo for the selected table
        var columnInfo = <?php echo json_encode($columnInfo); ?>;

        // Find the columns for the selected table
        var selectedTableColumns = columnInfo.find(table => table.table === selectedTable).columns;

        // Create an HTML table
        var tableHtml = '<table border="1">';
        tableHtml += '<tr><th></th><th>Field</th></tr>';

        // Populate the table rows with column information and checkboxes
        for (var i = 0; i < selectedTableColumns.length; i++) {
            tableHtml += '<tr>';
            tableHtml += '<td><input type="checkbox" class="checkbox-column' + dropdownNumber + '" id="' + selectedTableColumns[i].Field + '" onchange="checkForeignKeyButton()"></td>';
            tableHtml += '<td>' + selectedTableColumns[i].Field + '</td>';
            tableHtml += '</tr>';
        }

        tableHtml += '</table>';

        // Display the HTML table in a container
        var columnContainerId = 'columnInfoTableContainer' + dropdownNumber;
        document.getElementById(columnContainerId).innerHTML = tableHtml;
    }

    function checkForeignKeyButton() {
        var checkboxes1 = document.querySelectorAll('.checkbox-column1:checked');
        var checkboxes2 = document.querySelectorAll('.checkbox-column2:checked');
        var createForeignKeyBtn = document.getElementById('createForeignKeyBtn');

        if (checkboxes1.length > 0 && checkboxes2.length > 0) {
            createForeignKeyBtn.style.display = 'block';
        } else {
            createForeignKeyBtn.style.display = 'none';
        }
    }

    function createForeignKey() {
        var dropdown = document.getElementById('tableChoiceDropdown1');
        var table1 = dropdown.options[dropdown.selectedIndex].text;
        var dropdown1 = document.getElementById('tableChoiceDropdown2');
        var table2 = dropdown1.options[dropdown1.selectedIndex].text;
        var selectedField1 = '';
        var selectedField2 = '';

        var checkboxes1 = document.querySelectorAll('.checkbox-column1:checked');
        if (checkboxes1.length > 0) {
            selectedField1 = checkboxes1[0].id;
        }

        var checkboxes2 = document.querySelectorAll('.checkbox-column2:checked');
        if (checkboxes2.length > 0) {
            selectedField2 = checkboxes2[0].id;
        }


        var targetUrl = '<?= BASE_URL ?>vtl_gen/setForeignKey';

        var postData = {
            table1: table1,
            table2: table2,
            selectedField1: selectedField1,
            selectedField2: selectedField2
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', targetUrl, true);
        xhr.setRequestHeader('Content-type', 'application/json');
        var jsonData = JSON.stringify(postData);
        xhr.send(jsonData);

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        // Handle error responses here
                        openModal('response-modal');
                        const targetEl = document.getElementById('the-response');
                        targetEl.innerHTML = response.error;
                    } else {
                        // Success message
                        openModal('response-modal');
                        const targetEl = document.getElementById('the-response');
                        targetEl.innerHTML = response.message;
                    }
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    console.log('Response from server:', xhr.responseText);
                    openModal('response-modal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = 'Error parsing response: ' + xhr.responseText;
                }
            } else {
                console.error('Error:', xhr.status);
                console.log('Response from server:', xhr.responseText);
                openModal('response-modal');
                const targetEl = document.getElementById('the-response');
                targetEl.innerHTML = 'Request failed with status ' + xhr.status;
            }
        };
    }
</script>


<style>
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 1200px; /* Adjust max-width as needed */
        margin: 0 auto; /* Center the container horizontally */
    }

    .flex {
        display: flex;
        justify-content: center;
    }

    .dropdown-container {
        display: flex;
    }

    .dropdown-container > div {
        margin-right: 20px; /* Adjust margin as needed */
    }

    /* Adjust the width of the checkbox column */
    .checkbox-column {
        width: 5%; /* Adjust width as needed */
    }

    /* Set the width of the tables */
    .column-container table {
        width: 100%; /* Adjust width as needed */
    }

    input[type="checkbox"] {
        margin: 0.2em;
        top: 0.3em;
        position: relative;
        width: 1em;
        height: 1em;
    }
</style>



