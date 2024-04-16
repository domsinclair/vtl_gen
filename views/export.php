<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vtl_Generator_Export</title>
</head>
<body>
<h2 class="text-center">Vtl Data Generator: Export Database</h2>
<div class="flex">
    <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
</div>
<p>Select those tables to be exported and whether you want data from them exported as well.</p>
<table>
    <thead>
    <tr>
        <th><label><input type="checkbox" id="export-all">Export Table</label></th>
        <th>Tables</th>
        <th><label><input type="checkbox" id="export-data">Export Data</label></th> <!-- Select All checkbox for the right column -->
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tables as $index => $item): ?>
        <tr>
            <td><input type="checkbox" name="tables[]" value="<?php echo $item; ?>"></td>
            <td><?php echo $item; ?></td>
            <td><input type="checkbox" name="rightTables[]" value="<?php echo $item; ?>" disabled></td> <!-- Right checkbox column -->
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="flex">
    <button id="exportBtn" class="button">Export</button>
</div>
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


    document.getElementById("export-all").addEventListener("change", function() {
        var checkboxes = document.querySelectorAll("input[name='tables[]']");
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
            updateRightCheckbox(checkbox);
        }, this);
    });

    function updateRightCheckbox(checkbox) {
        var index = checkbox.getAttribute("value");
        var rightCheckbox = document.querySelector("input[name='rightTables[]'][value='" + index + "']");
        rightCheckbox.disabled = !checkbox.checked;
        rightCheckbox.checked = checkbox.checked && rightCheckbox.checked;
    }

    document.querySelectorAll("input[name='tables[]']").forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            updateRightCheckbox(this);
        });
    });

    document.getElementById("export-data").addEventListener("change", function() {
        var checkboxes = document.querySelectorAll("input[name='rightTables[]']");
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        }, this);
    });

    // Disable "Select All" checkbox for the right column if no checkboxes are checked on the right
    document.getElementById("export-data").addEventListener("change", function() {
        var rightCheckboxes = document.querySelectorAll("input[name='rightTables[]']");
        var selectAllRightCheckbox = document.getElementById("export-data");
        var isChecked = false;

        rightCheckboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                isChecked = true;
            }
        });

        selectAllRightCheckbox.disabled = !isChecked;
    });

    document.getElementById("exportBtn").addEventListener("click", function() {
        var tablesToExport = [];
        var tablesWithDataToExport = [];

        // Process left checkboxes
        var checkboxes = document.querySelectorAll("input[name='tables[]']:checked");
        checkboxes.forEach(function(checkbox) {
            tablesToExport.push(checkbox.value);
        });

        // Process right checkboxes
        var rightCheckboxes = document.querySelectorAll("input[name='rightTables[]']:checked");
        rightCheckboxes.forEach(function(checkbox) {
            tablesWithDataToExport.push(checkbox.value);
        });

        // Prepare the data to send
        var postData = {
            tablesToExport: tablesToExport,
            tablesWithDataToExport: tablesWithDataToExport
        };

        // Create a new XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Specify the PHP file or endpoint to handle the data
        var targetUrl = '<?= BASE_URL ?>vtl_gen-vtl_faker/exportDatabase';  // Adjust the URL accordingly

        // Open a POST request to the specified URL
        xhr.open('POST', targetUrl, true);

        // Set the content type to JSON
        xhr.setRequestHeader('Content-type', 'application/json');

        // Convert the data object to a JSON string
        var jsonData = JSON.stringify(postData);

        // Send the request with the JSON data
        xhr.send(jsonData);

        // Define a callback function to handle the response
        xhr.onload = function() {
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
    });
</script>

<style>
    input[type="checkbox"]
    {margin: 5px;}
    body{
        background-color: #f4eeee;
    }
</style>


