<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/vtl.css">
    <title>Vtl_Generator_ShowData</title>
</head>
<body>
<h2 class="text-center"><?= $headline ?></h2>
<section>
    <div class="container">
        <p>Select those foreign keys that you wish to delete</p>
    </div>
    <div class="container">
        <div class="flex" style="margin-bottom: 15px">
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
        </div>

        <?php
        // Check if rows data exists and has rows
        if (!empty($rows)) {
            ?>

            <table>
                <thead>
                <tr>
                    <th>Select</th>
                    <?php foreach (array_keys((array)$data['rows'][0]) as $header): ?>
                        <th><?= $header ?></th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data['rows'] as $row): ?>
                    <tr>
                        <td><input type="checkbox" class="row-checkbox" data-row='<?php echo json_encode($row); ?>'>
                        </td>
                        <?php foreach ($row as $value): ?>
                            <td><?= $value ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button id="deleteBtn" class="hidden" style="display: none;">Delete Foreign Key</button>
            <?php
        }
        ?>
    </div>
</section>
<div class="modal" id="response-modal" style="display: none">
    <div class="modal-heading">Generated Foreign Key</div>
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
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const deleteBtn = document.getElementById('deleteBtn');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                deleteBtn.classList.toggle('hidden', !anyChecked);
                deleteBtn.style.display = anyChecked ? 'block' : 'none';
            });
        });

        deleteBtn.addEventListener('click', function () {
            const selectedRows = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => JSON.parse(cb.getAttribute('data-row')));


            var targetUrl = '<?= BASE_URL ?>vtl_gen/deleteForeignKey';

            var postData = {
                selectedRows: selectedRows
            };
            console.log('post data', postData)
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
        });
    });
</script>