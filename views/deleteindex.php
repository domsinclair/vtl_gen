<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vtl_Generator_CreateIndex</title>
</head>
<body>
<h2 class="text-center">Vtl Data Generator: Delete Index</h2>
<div class="flex">
    <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
</div>
<p>Select a table in the database from the drop down below. If there are any indexes created by this module they will be shown in the table that appears.</p>
<p>Select those indexes that you wish to delete. </p>

<?php
//echo form_label($dropdownLabel);
$tableChoiceAttr['id'] = 'tableChoiceDropdown';
$tableChoiceAttr['onchange'] = 'selectedTable()';
echo form_dropdown('tableChoice', $tables, '', $tableChoiceAttr);
?>
<div id="columnInfoTableContainer" class="container"></div>
<div class="modal" id="response-modal" style="display: none">
    <div class="modal-heading">Deleted Indexes</div>
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

        // Find the indexInfo for the selected table
        var indexInfo = <?php echo json_encode($indexInfo); ?>;

        // Find the indexes for the selected table
        var selectedTableIndexesEntry = indexInfo.find(table => table.table === selectedTable);

        // Check if indexes are found for the selected table
        if (selectedTableIndexesEntry && selectedTableIndexesEntry.indexes.length > 0) {
            var selectedTableIndexes = selectedTableIndexesEntry.indexes.filter(index => index.Key_name !== 'PRIMARY');

            // Check if there are indexes other than PRIMARY
            if (selectedTableIndexes.length > 0) {
                // Create an HTML table
                var tableHtml = '<table border="1">';
                tableHtml += '<tr><th><input type="checkbox" id="selectAllCheckbox"></th><th>Non_unique</th><th>Key_name</th><th>Column_name</th></tr>';

                // Populate the table rows with index information
                for (var i = 0; i < selectedTableIndexes.length; i++) {
                    tableHtml += '<tr>';
                    tableHtml += '<td><input type="checkbox" class="rowCheckbox"></td>';
                    tableHtml += '<td>' + selectedTableIndexes[i].Non_unique + '</td>';
                    tableHtml += '<td>' + selectedTableIndexes[i].Key_name + '</td>';
                    tableHtml += '<td>' + selectedTableIndexes[i].Column_name + '</td>';
                    tableHtml += '</tr>';
                }

                tableHtml += '</table>';

                // Display the HTML table in a container
                document.getElementById('columnInfoTableContainer').innerHTML = tableHtml;

                // Add a button beneath the table (initially hidden)
                var buttonHtml = '<button id="submitBtn" onclick="submitForm()" style="display: none;">Delete Indexes</button>';
                document.getElementById('columnInfoTableContainer').insertAdjacentHTML('beforeend', buttonHtml);


                // Add event listeners for checkboxes
                var selectAllCheckbox = document.getElementById('selectAllCheckbox');
                var rowCheckboxes = document.querySelectorAll('.rowCheckbox');

                selectAllCheckbox.addEventListener('change', function() {
                    rowCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                    toggleSubmitButtonVisibility();
                });

                rowCheckboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        var allChecked = true;
                        rowCheckboxes.forEach(function(checkbox) {
                            if (!checkbox.checked) {
                                allChecked = false;
                            }
                        });
                        selectAllCheckbox.checked = allChecked;
                        toggleSubmitButtonVisibility();
                    });
                });

                function toggleSubmitButtonVisibility() {
                    var submitBtn = document.getElementById('submitBtn');
                    var anyCheckboxChecked = false;
                    rowCheckboxes.forEach(function(checkbox) {
                        if (checkbox.checked) {
                            anyCheckboxChecked = true;
                        }
                    });
                    submitBtn.style.display = anyCheckboxChecked ? 'block' : 'none';
                }
            } else {
                // If only the PRIMARY index is found, display a message
                var primaryIndexMessage = '<p>The selected table only has the PRIMARY index.</p>';
                document.getElementById('columnInfoTableContainer').innerHTML = primaryIndexMessage;
            }
        } else {
            // If no indexes are found, display a message
            var noIndexesMessage = '<p>No indexes were found on the selected table.</p>';
            document.getElementById('columnInfoTableContainer').innerHTML = noIndexesMessage;
        }
    }




    function submitForm() {
        // Get the selected table name from the dropdown
        var dropdown = document.getElementById('tableChoiceDropdown');
        var selectedTable = dropdown.options[dropdown.selectedIndex].text;

        // Get the values from the selected rows
        var selectedRows = [];
        var checkboxes = document.querySelectorAll('.rowCheckbox');
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                var row = {
                    keyName: checkbox.parentElement.nextElementSibling.nextElementSibling.textContent,
                    tableName: selectedTable
                };
                selectedRows.push(row);
            }
        });

        // Prepare the data to send
        var postData = {
            selectedTable: selectedTable,
            selectedRows: selectedRows
        };

        // Create a new XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Specify the PHP file or endpoint to handle the data
        var targetUrl = '<?= BASE_URL ?>vtl_gen-vtl_faker/deleteIndex';
        // Open a POST request to the specified URL
        xhr.open('POST', targetUrl, true);

        // Set the content type to JSON
        xhr.setRequestHeader('Content-type', 'application/json');

        // Convert the data object to a JSON string
        var jsonData = JSON.stringify(postData);

        // Send the request with the JSON data
        xhr.send(jsonData);

        // Define a callback function to handle the response
        xhr.onload = function () {
            if (xhr.status === 200) {

                    // Handle the response here
                    var response = xhr.responseText;
                    openModal('response-modal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = xhr.responseText;


            } else {
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