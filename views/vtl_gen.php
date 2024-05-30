<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="color-scheme" content="dark light">
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
<section>
    <div class="container">


        <div class="grid-container">
            <!-- Group Title for Data -->
            <div class="grid-item data-heading" colspan="4">Data Generation and Visualisation</div>

            <!-- Data Operation Buttons -->
            <div class="grid-item">
                <?php echo anchor('vtl_gen/createData', 'Create Data', array("class" => "button")); ?>
            </div>
            <div class="grid-item">
                <?php echo anchor('vtl_gen/deleteData', 'Delete Data', array("class" => "button")); ?>
            </div>
            <div class="grid-item">
                <?php echo anchor('vtl_gen/fetchLatestPkValues', 'Latest Pk Values', array("class" => "button")); ?>
            </div>
            <div class="grid-item" id="showButton">
                <button class="button" onclick="toggleDropdown()">Show Table Data</button>
            </div>

            <!-- Group Title for Indexes -->
            <div class="grid-item index-heading" colspan="4">Index and Foreign Key Operations</div>

            <!-- Index Operation Buttons -->
            <div class="grid-item">
                <?php echo anchor('vtl_gen/createIndex', 'Create Index', array("class" => "button")); ?>
            </div>
            <div class="grid-item">
                <?php echo anchor('vtl_gen/deleteIndex', 'Delete Index', array("class" => "button")); ?>
            </div>
            <div class="grid-item">
                <?php echo anchor('vtl_gen/showForeignKeys', 'Show Foreign Keys', array("class" => "button")); ?>
            </div>
            <div class="grid-item">
                <?php echo anchor('vtl_gen/createForeignKey', 'Create Foreign Key', array("class" => "button")); ?>
            </div>
            <div class="grid-item">
                <?php echo anchor('vtl_gen/showDeleteForeignKey', 'Delete Fk Key', array("class" => "button")); ?>
            </div>

            <!-- Group Title for Database Operations -->

            <div class="grid-item dbase-heading" colspan="4">Database Operations</div>

            <!-- Database Operation Buttons -->
            <div class="grid-item">
                <?php echo anchor('vtl_gen/createDatatable', 'Create Table', array("class" => "button")); ?>
            </div>
            <div class="grid-item">
                <?php echo anchor('vtl_gen/dropDatatable', 'Drop Table', array("class" => "button")); ?>
            </div>
            <div class="grid-item">
                <?php echo anchor('vtl_gen/export', 'Export Script', array("class" => "button")); ?>
            </div>

            <!-- Add two more buttons if needed, or leave these empty for now -->
            <div class="grid-item"></div>
            <div class="grid-item"></div>

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
                <?php
                $tableChoiceAttr['id'] = 'dropTableChoiceDropdown';
                $tableChoiceAttr['style'] = 'display: none;'; // Initially hide the dropdown
                $tableChoiceAttr['onchange'] = 'dropTable()';
                echo form_dropdown('tableChoice', $tables, '', $tableChoiceAttr);
                ?>
            </div>
        </section>
        <div class="container  text-center">
            <h2>Help</h2>
        </div>

        <section>
            <div class="container">
                <div><?php echo $markdownIntro; ?></div>
                <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            </div>
        </section>
    </div>
</section>
<a href="#" class="back-to-top">
    <span><img src="vtl_gen_module/help/images/vtluparrow.svg" </span>
</a>
<script src="<?= BASE_URL ?>vtl_gen_module/js/prism.js"></script>
</body>
</html>
<script>
    function toggleDropDatabaseDropdown() {
        var dropdown = document.getElementById('dropTableChoiceDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

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

    function dropTable() {

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
        /*display: grid;*/
        /*grid-template-columns: repeat(6, 1fr);*/
        /*grid-template-rows: auto auto;*/
        /*!*gap: 5px;*!*/
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 5px; /* Adjust the gap between grid items as needed */
        padding: 5px; /* Adjust the padding around the grid as needed */
    }

    .grid-item {
        /*!*background-color: #f2f2f2;*!*/
        /*!*padding: 5px;*!*/
        /*text-align: center;*/
        /*border: 1px solid #ccc; !* Optional: Add a border to grid items *!*/
        padding: 5px; /* Adjust the padding inside grid items as needed */
        text-align: center; /* Center the text in grid items */
        margin-bottom: 10px;

    }

    .button {
        border-radius: 10px;
        text-transform: capitalize;
        margin-bottom: 10px;
    }

    /*.data-heading {*/
    /*    grid-column: 1 / span 3;*/
    /*    margin-bottom: 2px;*/
    /*    font-size: large;*/
    /*    font-weight: bold;*/
    /*}*/

    /*.index-heading {*/
    /*    grid-column: 4 / span 2;*/
    /*    margin-bottom: 2px;*/
    /*    font-size: large;*/
    /*    font-weight: bold;*/
    /*}*/

    .data-heading, .index-heading, .dbase-heading {
        grid-column: span 4; /* Span all 4 columns */
        color: #f0f0f0; /* Optional: Add a background color for the headings */
        text-align: center; /* Center the text in headings */
        font-weight: bold; /* Make the headings bold */
        margin-top: 10px;
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