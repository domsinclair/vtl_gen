


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
<h1 class="text-center">Vtl Data Generator</h1>

<div class="grid-container">
    <div class="grid-item data-heading" colspan="3">Data</div>
    <div class="grid-item index-heading" colspan="2">Indexes</div>
    <div class="grid-item database-heading">Script</div>
    <div class="grid-item"><?php echo anchor('vtl_gen/createData', 'Create', array("class" => "button")); ?></div>
    <div class="grid-item" id="showButton"><button class="button" onclick="toggleDropdown()">Show</button></div>
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

<section>
    <div class="container">
        <div><?php echo $markdownCreateData; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<section>
    <h4 class="text-center">Create Data Gallery</h4>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[0]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[1]?>" >
    </div>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[2]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[3]?>" >
    </div>
    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div><?php echo $markdownShowData; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<section>
    <div class="container">
        <div><?php echo $markdownDeleteData; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<section>
    <h4 class="text-center">Delete Data Gallery</h4>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[7]?>" >
    </div>
    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div><?php echo $markdownCreateIndex; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<section>
    <h4 class="text-center">Create Index Gallery</h4>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[4]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[5]?>" >
    </div>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[6]?>" >
    </div>
    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>

<section>
    <div class="container">
        <div><?php echo $markdownDeleteIndex; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<section>
    <h4 class="text-center">Delete Index Gallery</h4>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[8]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[9]?>" >
    </div>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[10]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[11]?>" >
    </div>
    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div><?php echo $markdownExport; ?></div>
    </div>
</section>

<section>
    <h4 class="text-center">Export Database Gallery</h4>
    <div class = "container flex">
    <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[12]?>" >
    <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[13]?>" >
    </div>
</section>
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
    .flex{
        gap: 20px
    }

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

</style>