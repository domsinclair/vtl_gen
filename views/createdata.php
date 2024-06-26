<?php $picDirectoryExists = false; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--    <meta name="color-scheme" content="dark light">-->
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/vtl.css">
    <title>Vtl_Generator_CreateData</title>
</head>
<body>
<h2 class="text-center">Vtl Data Generator: Create Data</h2>
<section>
    <div class="container">
        <div class="flex">
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
        </div>
        <?php //echo '<p>' . anchor('vtl_gen/showHelp', 'Show Vtl Generator Help', array("class" => "button")) . '</p>'; ?>
        <p>Select the table in the database from the drop down below for which you wish to create some fake data.</p>
        <p>Select those columns into which you want to add data, or just check the checkbox in the header if you want to
            select
            all the rows. </p>
        <p><b>Do not select fields that are set to auto increment.</b></p>
        <?php
        //echo form_label($dropdownLabel);
        $tableChoiceAttr['id'] = 'tableChoiceDropdown';
        $tableChoiceAttr['onchange'] = 'selectedTable()';
        echo form_dropdown('tableChoice', $tables, '', $tableChoiceAttr);

        ?>

        <!-- HTML element for a progress bar -->
        <!--<div class="progress-bar">-->
        <!--    <div class="progress" id="progress" style=" width = 250px; background-color: #3b0a49;"></div>-->
        <!--</div>-->
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
        <div class="modal" id="image-transfer-response-modal" style="display: none">
            <div class="modal-heading">Transferred Images</div>
            <div class="modal-body">
                <p id="the-response"></p>
                <p class="text-center">
                    <button onclick="closeModal()" class="alt">Close</button>
                </p>
            </div>
        </div>
        <div>
            <input type="hidden" id="picDirectoryExists" value="<?php echo $picDirectoryExists ? 'true' : 'false'; ?>">
        </div>
    </div>
</section>

<script src="<?= BASE_URL ?>js/app.js"></script>
</body>
</html>
<script>
    // const progressBar = document.getElementById("progress");
    // // Global variable to store folder progress
    // let folderProgress = 0;


    async function setPictureDirectoryExistsForSelectedTableModule(selectedTable) {
        <?php $picDirectoryExists = false;?>
        var postData = {
            selectedTable: selectedTable
        };

        try {
            // Create a new XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Specify the PHP file or endpoint to handle the data
            var targetUrl = '<?= BASE_URL ?>vtl_gen-vtl_faker/getPictureFolderExists';

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
                    // Parse the response as needed
                    var responseObject = JSON.parse(response);
                    var picDirectoryExists = responseObject.picDirectoryExists;
                    // Now you can use the picDirectoryExists variable


                    // Update the content of the hidden input field
                    var picDirectoryExistsInput = document.getElementById('picDirectoryExists');
                    picDirectoryExistsInput.value = picDirectoryExists ? 'true' : 'false';


                } else {
                    // Handle error responses here
                    console.error('Error:', xhr.status);
                }
            };
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function selectedTable() {
        // Get the dropdown element
        var dropdown = document.getElementById('tableChoiceDropdown');

        // Get the selected value
        var selectedTable = dropdown.options[dropdown.selectedIndex].text;

        await setPictureDirectoryExistsForSelectedTableModule(selectedTable);

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


        // Create a flex container for the buttons, progress bar, and iframe
        var flexContainerHtml = '<div class="flex-container">';
        document.getElementById('columnInfoTableContainer').insertAdjacentHTML('beforeend', flexContainerHtml);

// Add the button beneath the table (initially hidden)
        var submitBtnHtml = '<button id="submitBtn" onclick="submitForm()" style="display: none !important;margin-bottom: 15px;">Generate Fake Data</button>';
        document.querySelector('.flex-container').insertAdjacentHTML('beforeend', submitBtnHtml);

// Add the button beneath the table (initially hidden)
        var movePicsBtnHtml = '<button id="movePicsBtn" onclick="movePictures()" style="display: none; margin-bottom: 15px;">Transfer Images</button>';
        document.querySelector('.flex-container').insertAdjacentHTML('beforeend', movePicsBtnHtml);

// Add a progress bar
        var progressHtml = '<div class="progress-bar" id="progress-bar"><div class="progress" id="progress"></div></div>';
        document.querySelector('.flex-container').insertAdjacentHTML('beforeend', progressHtml);


// Close the flex container
        document.getElementById('columnInfoTableContainer').insertAdjacentHTML('beforeend', '</div>');


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
        document.addEventListener('change', function (event) {
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
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = source.checked;
                // Trigger change event on the checkbox
                checkbox.dispatchEvent(new Event('change'));
            });
        }
    }


    async function processRecords(totalRows) {
        // Get the selected table name from the dropdown
        var dropdown = document.getElementById('tableChoiceDropdown');
        var selectedTable = dropdown.options[dropdown.selectedIndex].text;

        var progressBar = document.getElementById('progress');
        var progress = 0;

        // Define a function to handle each record asynchronously
        async function processRecord(recordId) {
            return new Promise((resolve, reject) => {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= BASE_URL ?>vtl_gen-vtl_faker/copyImageForRecord', true);
                xhr.setRequestHeader('Content-type', 'application/json');

                // Prepare the data to send
                var data = {
                    recordId: recordId,
                    selectedTable: selectedTable
                };

                // Convert the data object to a JSON string
                var jsonData = JSON.stringify(data);

                // Define a callback function to handle the response
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // Update progress bar
                        progress++;
                        var percent = Math.round((progress / totalRows) * 100);
                        progressBar.style.width = percent + '%';
                        progressBar.textContent = percent + '%';

                        // Resolve the Promise
                        resolve();
                    } else {
                        // Reject the Promise on error
                        reject('Request from process images failed with status ' + xhr.responseText);
                    }
                };

                xhr.onerror = function () {
                    // Reject the Promise on connection error
                    reject('Request failed');
                };

                // Send the request with the JSON data
                xhr.send(jsonData);
            });
        }

        // Loop through records and process them asynchronously
        for (var i = 1; i <= totalRows; i++) {
            try {
                await processRecord(i);
            } catch (error) {
                // Handle errors here if needed
                console.error(error);
                openModal('image-transfer-response-modal');
                const targetEl = document.getElementById('the-response');
                targetEl.innerHTML = error;
                return; // Stop processing further records on error
            }
        }

        // If all records processed, display success message
        openModal('image-transfer-response-modal');
        const targetEl = document.getElementById('the-response');
        targetEl.innerHTML = 'Images copied successfully.';
    }


    function movePictures() {
        // Get the selected table name from the dropdown
        var dropdown = document.getElementById('tableChoiceDropdown');
        var selectedTable = dropdown.options[dropdown.selectedIndex].text;

        // Prepare the data to send
        var postData = {
            selectedTable: selectedTable
        };

        try {
            // Create a new XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Specify the PHP file or endpoint to handle the data
            var targetUrl = '<?= BASE_URL ?>vtl_gen-vtl_faker/setImageFoldersAndTransferImages';

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
                    var response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        // Handle error responses here
                        openModal('response-modal');
                        const targetEl = document.getElementById('the-response');
                        targetEl.innerHTML = response.error;
                    } else {
                        // Start processing records
                        processRecords(response.totalRows);
                    }
                } else {
                    // Handle error responses here
                    openModal('response-modal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = 'Request failed with status ' + xhr.status;
                }
            };
        } catch (error) {
            openModal('response-modal');
            const targetEl = document.getElementById('the-response');
            targetEl.innerHTML = error;
        }
    }


    // Function to check if a string is valid JSON
    function isValidJson(str) {
        try {
            JSON.parse(str);
            return true;
        } catch (error) {
            return false;
        }
    }

    function insertDataViaTransaction(selectedTable, selectedRows, numRows) {

    }

    function submitForm1() {
        // this is now going to act as little more that a redirect now
        // less than 2000 rows can be created as before, more will cause transactions to kick in.

        // we need the basic data to fire off the process

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

        function isDivisable(dividend, divisor) {
            return dividend % divisor === 0;
        }

        // divide numRows by 2000
        if (isDivisable(numRows, 2000)) {

        }
        if (numRows > 1000) {
            // divide numRows by 2000
            if (isDivisable(numRows, 2000)) {
                // head off to do a transaction
                insertDataViaTransaction(selectedTable, selectedRows, numRows);
            } else {
                //do 1000 or whatever rows
                insertDataViaTransaction(selectedTable, selectedRows, 1000);
                //do the rest
                insertDataViaTransaction(selectedTable, selectedRows, numRows);
            }

        } else {
            //head off down the old path
        }
    }

    function submitForm() {

        // Get the value of picDirectoryExists
        var picDirectoryExists = document.getElementById('picDirectoryExists').value === 'true';

        // Check if picDirectoryExists is true
        if (picDirectoryExists) {

            // Hide the "Generate Fake Data" button
            document.getElementById('submitBtn').style.display = 'none';
            // Show the "Transfer Images" button
            document.getElementById('movePicsBtn').style.display = 'block';
        }


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
    :root {
        --max-progress-width: 500px;
        --progress-height: 30px;
        --border-radius: 50px;
    }

    .progress-bar {
        max-width: var(--max-progress-width);
        height: var(--progress-height);
        border-radius: var(--border-radius);
        overflow: hidden;
        position: relative;
        display: block;
    }

    .progress {
        background-color: var(--primary);
        /*transition: width 0.1s ease-in-out;*/
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 16px;
        position: absolute;
        width: 0;

    }

    input[type="checkbox"] {
        margin: 3px;
        text-align: center;
        align-self: auto;
    }

    /*body {*/
    /*    background-color: #f4eeee;*/
    /*}*/
</style>