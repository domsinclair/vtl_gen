<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vtl_Generator_CreateData</title>
</head>
<body>
<h2 class="text-center">Vtl Data Generator: Create Data</h2>
<div class="flex">
    <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
</div>
<?php //echo '<p>' . anchor('vtl_gen/showHelp', 'Show Vtl Generator Help', array("class" => "button")) . '</p>'; ?>
<p>Select the table in the database from the drop down below for which you wish to create some fake data.</p>
<p>Select those columns into which you want to add data, or just check the checkbox in the header if you want to select all the rows. </p>
<p><b>Do not select fields that are set to auto increment.</b></p>
<?php
//echo form_label($dropdownLabel);
$tableChoiceAttr['id'] = 'tableChoiceDropdown';
$tableChoiceAttr['onchange'] = 'selectedTable()';
echo form_dropdown('tableChoice', $tables, '', $tableChoiceAttr);

?>



<div id="columnInfoTableContainer" class="container"></div>
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

    function selectedTable() {
        // Get the dropdown element
        var dropdown = document.getElementById('tableChoiceDropdown');

        // Get the selected value
        var selectedTable = dropdown.options[dropdown.selectedIndex].text;

        // Find the columnInfo for the selected table
        var columnInfo = <?php echo json_encode($columnInfo); ?>;

        // Find the columns for the selected table
        var selectedTableColumns = columnInfo.find(table => table.table === selectedTable).columns;

        // Create an HTML table
        var tableHtml = '<table border="1">';

        tableHtml += '<tr><th><input type="checkbox" id="selectAllCheckbox" ></th><th>Field</th><th>Type</th><th>Null Allowed</th><th>Key</th><th>Default</th><th>Extra</th></tr>';

        // Populate the table rows with column information
        for (var i = 0; i < selectedTableColumns.length; i++) {
            // Exclude the column if it's a primary key with auto_increment
            if (!(selectedTableColumns[i].Key === 'PRI' && selectedTableColumns[i].Extra.includes('auto_increment'))) {
                tableHtml += '<tr>';
                tableHtml += '<td><input type="checkbox" class="checkbox"></td>';
                tableHtml += '<td>' + selectedTableColumns[i].Field + '</td>';
                tableHtml += '<td>' + selectedTableColumns[i].Type + '</td>';
                tableHtml += '<td>' + selectedTableColumns[i].Null + '</td>';
                tableHtml += '<td>' + selectedTableColumns[i].Key + '</td>';
                tableHtml += '<td>' + selectedTableColumns[i].Default + '</td>';
                tableHtml += '<td>' + selectedTableColumns[i].Extra + '</td>';
                tableHtml += '</tr>';
            }
        }


        tableHtml += '</table>';

        // Display the HTML table in a container
        document.getElementById('columnInfoTableContainer').innerHTML = tableHtml;

        // Add an input field beneath the table (initially hidden)
        var containerHtml = '<div id="numRowsContainer" style="display: none;">';
        containerHtml += '<label for="numRows">Enter the number of rows of data you wish to generate:</label>';
        containerHtml += '<input type="number" id="numRows" name="numRows" min="1" value="1">';
        containerHtml += '</div>';
        document.getElementById('columnInfoTableContainer').insertAdjacentHTML('beforeend', containerHtml);

        // Add a button beneath the table (initially hidden)
        var buttonHtml = '<button id="submitBtn" onclick="submitForm()" style="display: none;">Generate Fake Data</button>';
        document.getElementById('columnInfoTableContainer').insertAdjacentHTML('beforeend', buttonHtml);


        var checkboxes = document.querySelectorAll('.checkbox');
        var numRowsInput = document.getElementById('numRows');
        var submitBtn = document.getElementById('submitBtn');
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {

                numRowsContainer.style.display = checkboxesChecked() ? "block" : "none";
                submitBtn.style.display = checkboxesChecked() ? "block" : "none";
            });
        });
        // Add an event listener for the "Select All" checkbox
        document.addEventListener('change', function(event) {
            var target = event.target;
            if (target && target.id === 'selectAllCheckbox') {
                toggleAllCheckboxes(target);
            }
        });
        function checkboxesChecked() {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    return true;
                }
            }
            return false;
        }

        function toggleAllCheckboxes(source) {
            // Get all checkboxes within the same table
            var table = source.closest('table');
            var checkboxes = table.querySelectorAll('.checkbox');

            // Toggle each checkbox based on the state of the "Select All" checkbox
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = source.checked;
                // Trigger change event on the checkbox
                checkbox.dispatchEvent(new Event('change'));
            });
        }
    }


    function submitForm() {

        // Get the selected table name from the dropdown
        var dropdown = document.getElementById('tableChoiceDropdown');
        var selectedTable = dropdown.options[dropdown.selectedIndex].text;

        // Get the values from the selected rows
        var selectedRows = [];
        var checkboxes = document.querySelectorAll('.checkbox');
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                var row = {
                    field: checkbox.parentElement.nextElementSibling.textContent,
                    type: checkbox.parentElement.nextElementSibling.nextElementSibling.textContent
                };
                selectedRows.push(row);
            }
        });

        // Get the value from the numRows input field
        var numRows = document.getElementById('numRows').value;

        // Prepare the data to send
        var postData = {
            selectedTable: selectedTable,
            selectedRows: selectedRows,
            numRows: numRows  // the number of rows of data to be created
        };


        // Create a new XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Specify the PHP file or endpoint to handle the data
        var targetUrl = '<?= BASE_URL ?>vtl_gen-vtl_faker/createFakes';  //createFakes
        // Open a POST request to the specified URL
        xhr.open('POST', targetUrl, true);

        // Set the content type to JSON
        xhr.setRequestHeader('Content-type', 'application/json');

        // Convert the data object to a JSON string
        var jsonData = JSON.stringify(postData);

        // Send the request with the JSON data
        xhr.send(jsonData);
        // console.log('This is the post data:',jsonData);
        // Define a callback function to handle the response
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Handle the response here
                var response = xhr.responseText;

                if (response.startsWith('New Record Id')) {
                    // Display a success message or do whatever you want
                    //console.log('Success:', response);
                    openModal('response-modal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = xhr.responseText;
                } else if (response.startsWith('Number of records inserted')) {
                    // Display a success message or do whatever you want
                    //console.log('Success:', response);
                    openModal('response-modal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = xhr.responseText;
                } else if (response === 'No api Exists') {
                    // Display a message indicating no API exists
                    console.log('No API Exists');
                } else {
                    // Handle other cases or errors
                    //console.error('Unexpected response:', response);
                    openModal('response-modal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = xhr.responseText;
                }
            } else {
                //console.error('Error:', xhr.status);
                openModal('response-modal');
                const targetEl = document.getElementById('the-response');
                targetEl.innerHTML = xhr.status;
            }
        };

    }

</script>

<style>
    input[type="checkbox"]
    {margin: 5px;}
</style>