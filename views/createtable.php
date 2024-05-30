<?php ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/vtl.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/tabulator.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/tabulator_midnight.css">
    <script type="text/javascript" src="<?= BASE_URL ?>vtl_gen_module/js/tabulator.js"
    "></script>
    <title>Vtl_Generator_Create_Table</title>
</head>
<body>
<h2 class="text-center"><?= $headline ?></h2>
<section>
    <div class="container">
        <div class="flex" style="margin-bottom: 15px">
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
        </div>
        <input type="text" id="table-name-input" placeholder="Enter table name">
        <div class="flex">
            <h3>Columns</h3>
            <button id="add-row" class="icon-button" title="Add Row">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px"
                     viewBox="0 0 32 32" enable-background="new 0 0 32 32" xml:space="preserve">
  <g id="icon">
      <path d="M15.5,1C7.4919,1,1,7.4919,1,15.5s6.4919,14.5,14.5,14.5s14.5,-6.4919,14.5,-14.5S23.5081,1,15.5,1zM25,16.6c0,0.2209,-0.1791,0.4,-0.4,0.4h-7.6v7.6c0,0.2209,-0.1791,0.4,-0.4,0.4h-2.2c-0.2209,0,-0.4,-0.1791,-0.4,-0.4v-7.6H6.4c-0.2209,0,-0.4,-0.1791,-0.4,-0.4v-2.2c0,-0.2209,0.1791,-0.4,0.4,-0.4h7.6V6.4c0,-0.2209,0.1791,-0.4,0.4,-0.4h2.2c0.2209,0,0.4,0.1791,0.4,0.4v7.6h7.6c0.2209,0,0.4,0.1791,0.4,0.4V16.6z"
            fill-rule="evenodd" fill="#00A651"/>
  </g>
</svg>
            </button>
        </div>

        <div id="datatable"></div>
        <button id="save-table">Create Table</button>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var table = new Tabulator("#datatable", {
            layout: "fitColumns",
            columns: [
                {title: "Field Name", field: "field", editor: "input"},
                {
                    title: "Data Type", field: "type", editor: "list", editorParams: {
                        values: {
                            "autoincrement": "Autoincrement",
                            "varchar": "Varchar",
                            "varchar(50)": "Varchar(50)",
                            "varchar(100)": "Varchar(100)",
                            "varchar(255)": "Varchar(255)",
                            "text": "Text",
                            "int": "Int",
                            "tinyint": "Tinyint",
                            "bigint": "Bigint",
                            "decimal": "Decimal",
                            "float": "Float",
                            "double": "Double",
                            "boolean": "Boolean",
                            "date": "Date",
                            "datetime": "Datetime",
                            "time": "Time",
                            "timestamp": "Timestamp",
                            "char": "Char",
                            "binary": "Binary",
                            "varbinary": "Varbinary",
                            "blob": "Blob"

                        },
                        autocomplete: true
                    }
                },
                {title: "Nullable", field: "nullable", editor: "tickCross", formatter: "tickCross", width: 150},
                {title: "Default Value", field: "default", editor: "input"},
                {title: "Primary Key", field: "primary", editor: "tickCross", formatter: "tickCross", width: 170},
                {
                    title: '',
                    formatter: deleteIcon,
                    width: 40,
                    hozAlign: "center",
                    cellClick: function (e, cell) {
                        cell.getRow().delete();
                    }
                }
            ],

        });


        // Add row button functionality
        document.getElementById('add-row').addEventListener('click', function () {
            table.addRow({});
        });

        // Save table button functionality
        document.getElementById('save-table').addEventListener('click', function () {
            var tableData = table.getData();
            console.log(tableData); // Here you would send this data to your server
        });

        function deleteIcon(cell, formatterParams, onRendered) {
            return "<span class='delete-button'>&times;</span>";
        }

        table.on("rowUpdated", function (row) {

        });
    });


</script>
</body>
</html>
<style>
    .flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .icon-button {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .icon-button:hover {
        background: transparent;
    }

    .icon-button svg {
        width: 32px;
        height: 32px;
    }

    #table-name-input {
        width: 40%;
        padding: 8px;
        box-sizing: border-box;
        font-size: 16px;
    }

    .delete-button {
        color: darkred;
        cursor: pointer;
        font-weight: bold;
        font-size: 20px;
    }
</style>