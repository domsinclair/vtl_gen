<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/vtl.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/prism.css">
    <title>Vtl_Generator</title>
    <style>
        .flex {
            display: flex;
            justify-content: center; /* Center items horizontally */
            align-items: center; /* Center items vertically */
            gap: 4px;
        }

    </style>
</head>

<body>
<h2 class="text-center">Vtl Data Generator: Navigation and Help</h2>

<div class="grid-container">
    <div class="grid-item data-heading" colspan="3">Data</div>
    <div class="grid-item index-heading" colspan="2">Indexes</div>
    <div class="grid-item database-heading">Script</div>
    <div class="grid-item"><?php echo anchor('vtl_gen/createData', 'Create', array("class" => "button")); ?></div>
    <div class="grid-item" id="showButton">
        <button class="button" onclick="toggleDropdown()">Show</button>
    </div>
    <div class="grid-item"><?php echo anchor('vtl_gen/deleteData', 'Delete', array("class" => "button")); ?></div>
    <div class="grid-item"><?php echo anchor('vtl_gen/createIndex', 'Create', array("class" => "button")); ?></div>
    <div class="grid-item"><?php echo anchor('vtl_gen/deleteIndex', 'Delete', array("class" => "button")); ?></div>
    <div class="grid-item"><?php echo anchor('vtl_gen/export', 'Export', array("class" => "button")); ?></div>
</div>

<section>
    <div class="container">
        <?php
        $tableChoiceAttr['id'] = 'tableChoiceDropdown';
        $tableChoiceAttr['style'] = 'display: none;'; // Initially hide the dropdown
        $tableChoiceAttr['onchange'] = 'selectedTable()';
        echo form_dropdown('tableChoice', $tables, '', $tableChoiceAttr);
        ?>
    </div>
</section>
<section>
    <div class="container">
        <div><?php echo $markdownIntro; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<script src="<?= BASE_URL ?>vtl_gen_module/js/prism.js"></script>
</body>
</html>
<script>
    function toggleDropdown() {
        var dropdown = document.getElementById('tableChoiceDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

    function selectedTable() {
        // Get the dropdown element
        var dropdown = document.getElementById('tableChoiceDropdown');

        // Get the selected value
        var selectedTable = dropdown.options[dropdown.selectedIndex].text;

        // Construct the URL with the selected table as a query parameter
        // Redirect to the URL
        window.location.href = '<?= BASE_URL ?>vtl_gen/showData?selectedTable=' + encodeURIComponent(selectedTable);
    }
</script>
<style>
    .flex {
        gap: 20px
    }

    /*body {*/
    /*    background-color: #f4eeee;*/
    /*}*/

    .grid-container {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        grid-template-rows: auto auto;
        /*gap: 5px;*/
    }

    .grid-item {
        /*background-color: #f2f2f2;*/
        /*padding: 5px;*/
        text-align: center;
    }

    .data-heading {
        grid-column: 1 / span 3;
        margin-bottom: 2px;
        font-size: large;
        font-weight: bold;
    }

    .index-heading {
        grid-column: 4 / span 2;
        margin-bottom: 2px;
        font-size: large;
        font-weight: bold;
    }

    .database-heading {
        grid-column: 6;
        margin-bottom: 2px;
        font-size: large;
        font-weight: bold;
    }

    /*pre {*/
    /*    white-space: nowrap;*/
    /*    max-width: 100%;*/
    /*    overflow: auto;*/
    /*    background-color: #393838 !important;*/
    /*    border-color: #6b6b6b !important;*/
    /*    border-radius: 6px;*/
    /*    color: #f1e4e4 !important;*/
    /*    text-shadow: 0 1px 0 #444444 !important;*/
    /*    white-space: pre-wrap;*/
    /*    padding: 1em 1em;*/
    /*}*/
</style>