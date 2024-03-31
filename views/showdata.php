<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vtl_Generator_ShowData</title>
</head>
<body>
<h2 class="text-center">Vtl Data Generator: Show Data</h2>
<div class="flex">
    <?php echo anchor('vtl_gen/index', 'Back', array("class" => "button")); ?>
</div>
<p>Select the table in the database from the drop down below for which you wish to see the data.</p>
<?php
$tableChoiceAttr['id'] = 'tableChoiceDropdown';
$tableChoiceAttr['onchange'] = 'selectedTable()';
echo form_dropdown('tableChoice', $tables, '', $tableChoiceAttr);

?>

<div class="flex">
    <?php echo anchor('vtl_gen-vtl_faker/retrieveDataFromSelectedTable', 'Back', array("class" => "button")); ?>
</div>-
</body>
</html>
