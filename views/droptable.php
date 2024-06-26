<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/vtl.css">
    <title>Vtl_Generator_DropTables</title>
</head>
<body>
<h2 class="container, text-center"><?= $headline ?></h2>
<section>
    <div class="container">
        <div class="flex">
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
        </div>
        <p>Select those tables you wish to drop.</p>


        <table>
            <thead>
            <tr>
                <th><label><input type="checkbox" id="select-all"> </label></th>
                <th>Tables</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tables as $index => $item): ?>
                <tr>
                    <td><input type="checkbox" name="tables[]" value="<?php echo $item; ?>"></td>
                    <td><?php echo $item; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>


        <div class="flex">
            <button id="dropTableBtn" class="button">Drop Table</button>
        </div>
    </div>
</section>
<div id="columnInfoTableContainer" class="container"></div>
<div class="modal" id="response-modal" style="display: none">
    <div class="modal-heading">Dropped Table</div>
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
    document.getElementById("select-all").addEventListener("change", function () {
        var checkboxes = document.querySelectorAll("input[type='checkbox'][name='tables[]']");
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = this.checked;
        }, this);
    });

    document.getElementById("dropTableBtn").addEventListener("click", function () {
        var selectedTables = [];
        var checkboxes = document.querySelectorAll("input[type='checkbox'][name='tables[]']:checked");
        checkboxes.forEach(function (checkbox) {
            selectedTables.push(checkbox.value);
        });


        // Prepare the data to send
        var postData = {
            selectedTables: selectedTables

        };

        // Create a new XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Specify the PHP file or endpoint to handle the data
        var targetUrl = '<?= BASE_URL ?>vtl_gen-vtl_faker/dropTable';  // Adjust the URL accordingly
        console.log(targetUrl);
        // Open a POST request to the specified URL
        xhr.open('POST', targetUrl, true);

        // Set the content type to JSON
        xhr.setRequestHeader('Content-type', 'application/json');

        // Convert the data object to a JSON string
        var jsonData = JSON.stringify(postData);

        // Send the request with the JSON data
        xhr.send(jsonData);
        //console.log(jsonData);
        // Define a callback function to handle the response
        xhr.onload = function () {
            console.log(xhr.responseText);
            if (xhr.status === 200) {
                // Handle the response here
                var response = xhr.responseText;
                //console.log(xhr.responseText)
                if (response.startsWith('Operation completed successfully.') || response.startsWith('Number of records inserted')) {
                    openModal('response-modal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = xhr.responseText;
                } else {
                    openModal('response-modal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = xhr.responseText;
                }
            } else {
                openModal('response-modal');
                const targetEl = document.getElementById('the-response');
                targetEl.innerHTML = xhr.status;
            }
        };
    });
</script>

<style>
    input[type="checkbox"] {
        margin: 6px;
        align-self: inherit;
    }

    label {
        margin: 0;
    }


</style>