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
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/prism.css">
    <script type="text/javascript" src="<?= BASE_URL ?>vtl_gen_module/js/tabulator.js"></script>
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
        <button id="save-table">Generate Sql Statement</button>
</section>
<section>
    <div class="container">
        <pre>
            <code id="sqlCode" class="language-sql">

             </code>
        </pre>
    </div>
    <div class="container">
        <div class="flex">
            <button id="generate-table">Generate Table</button>
            <button id="save-sql">Save SQL</button>
            <button id="use-existing-sql">Use Existing SQL</button>
            <!--            <button id="create-module">Create Module</button>-->
        </div>
    </div>
</section>
<div class="modal" id="tableCreationModal" style="display: none">
    <div id="modal_header" class="modal-heading">Table Creation</div>
    <div class="modal-body">
        <p id="the-response"></p>
        <p class="text-center">
            <button onclick="closeModal()" class="alt">Close</button>
        </p>
    </div>
</div>
<script>
    var isSqlLoaded = false;
    document.addEventListener('DOMContentLoaded', function () {
        var table = new Tabulator("#datatable", {
            layout: "fitColumns",
            tabEndNewRow: {nullable: true, unique: false},
            columns: [
                {title: "Field Name", field: "colname", editor: "input"},
                {
                    title: "Data Type", field: "type", editor: "list", editorParams: {
                        values: {
                            "autoincrement": "Autoincrement",
                            "varchar": "Varchar",
                            "varchar(10)": "Varchar(10)",
                            "varchar(15)": "Varchar(15)",
                            "varchar(25)": "Varchar(25)",
                            "varchar(50)": "Varchar(50)",
                            "varchar(75)": "Varchar(75)",
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
                            "blob": "Blob",
                            "uuid": "Uuid"
                        },
                        autocomplete: true
                    },
                    cellEdited: function (cell) {
                        var value = cell.getValue();
                        switch (value) {
                            case "autoincrement":
                                cell.getRow().update({nullable: false});
                                break;
                            case "timestamp":
                                cell.getRow().update({nullable: false});
                                cell.getRow().update({default: 'CURRENT_TIMESTAMP'});
                                break;
                            case "uuid":
                                cell.getRow().update({nullable: false});
                                cell.getRow().update({default: 'UUID()'});
                                break;
                            // Add more cases here if needed
                            default:
                                // Default case if needed
                                break;
                        }
                    }
                },
                {
                    title: "Nullable",
                    field: "nullable",
                    editor: "tickCross",
                    hozAlign: "center",
                    vertAlign: "middle",
                    formatter: "tickCross",
                    width: 100
                },
                {title: "Default Value", field: "default", editor: "input"},
                {
                    title: "Primary Key",
                    field: "primary",
                    vertAlign: "middle",
                    hozAlign: "center",
                    editor: "tickCross",
                    formatter: "tickCross",
                    width: 120
                },
                {
                    title: "Unique",
                    field: "unique",
                    vertAlign: "middle",
                    hozAlign: "center",
                    editor: "tickCross",
                    formatter: "tickCross",
                    width: 100
                },
                {
                    title: '',
                    formatter: function (cell, formatterParams, onRendered) {
                        var span = document.createElement("span");
                        span.className = "tabulator-button tabulator-button-cross custom-button-cross"; // Apply custom class
                        span.innerHTML = "&times;";
                        return span;
                    },
                    width: 40,
                    hozAlign: "center",
                    vertAlign: "middle",
                    cellClick: function (e, cell) {
                        cell.getRow().delete();
                    }
                }
            ]
        });


        setTimeout(function () {
            table.addRow({nullable: true, unique: false});
        }, 0);

        document.getElementById('add-row').addEventListener('click', function () {
            table.addRow({nullable: true, unique: false});
        });

        document.getElementById('save-table').addEventListener('click', function () {
            var tableName = document.getElementById('table-name-input').value;
            var tableData = table.getData();
            var sql = generateSQLCreateStatement(tableName, tableData);
            document.getElementById('sqlCode').innerText = sql;
            Prism.highlightAll(); // Re-highlight the code block
        });

        // Add row above the double-clicked row
        table.on("rowDblClick", function (e, row) {
            var rowIndex = row.getPosition();
            table.addRow({nullable: true, unique: false}, true, rowIndex);
        });

        table.on("tableBuilt", function () {
            table.navigateNext();
        });

        function deleteIcon(cell, formatterParams, onRendered) {
            return "<span class='delete-button'>&times;</span>";
        }

        function generateSQLCreateStatement(tableName, columns) {
            var sql = '';
            if (!tableName) {
                alert('Please enter a table name.');
                return '';
            }

            sql = `CREATE TABLE IF NOT EXISTS ${tableName} (\n`;
            var primaryKeys = [];
            columns.forEach((column, index) => {
                sql += `  ${column.colname} `;
                if (column.type === 'autoincrement') {
                    sql += 'int(11)';
                } else {
                    sql += column.type;
                }
                if (column.primary) primaryKeys.push(column.colname);
                if (column.nullable === false) sql += ' NOT NULL';
                if (column.default) {
                    switch (column.default) {
                        case 'CURRENT_TIMESTAMP':
                            sql += ' DEFAULT CURRENT_TIMESTAMP';
                            break;
                        case 'CURRENT_DATE':
                            sql += ' DEFAULT CURRENT_DATE';
                            break;
                        case 'CURRENT_TIME':
                            sql += ' DEFAULT CURRENT_TIME';
                            break;
                        case 'UTC_TIMESTAMP':
                            sql += ' DEFAULT UTC_TIMESTAMP';
                            break;
                        case 'UNIX_TIMESTAMP':
                            sql += ' DEFAULT UNIX_TIMESTAMP';
                            break;
                        case 'UUID()':
                            sql += ' DEFAULT UUID()';
                            break;
                        // Add cases for other default values as needed
                        default:
                            sql += ` DEFAULT '${column.default}'`;
                    }
                }
                if (column.type === 'autoincrement') {
                    sql += ' AUTO_INCREMENT';
                }
                if (column.unique) {
                    sql += ' UNIQUE';
                }
                sql += ',\n';
            });

            if (primaryKeys.length > 0) {
                sql += `  PRIMARY KEY (${primaryKeys.join(', ')})\n`;
            }

            sql = sql.replace(/,\n$/, ''); // Remove the last comma and newline
            sql += '\n);';
            return sql;
        }

        // Generate Table button functionality
        document.getElementById('generate-table').addEventListener('click', function () {
            var tableName = document.getElementById('table-name-input').value;
            var tableData = table.getData();
            var sql;
            if (isSqlLoaded) {
                // If SQL script is loaded, use the code block content
                sql = document.getElementById('sqlCode').innerText;
            } else {
                // Otherwise, generate SQL statement from table data
                sql = generateSQLCreateStatement(tableName, tableData);
            }
            var xhr = new XMLHttpRequest();
            var targetUrl = '<?= BASE_URL ?>vtl_gen/createNewDataTable';
            xhr.open('POST', targetUrl, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify({sql: sql}));
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Parse the JSON response
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        openModal('tableCreationModal');
                        const targetEl = document.getElementById('the-response');
                        targetEl.innerHTML = response.message;
                    } else {
                        openModal('tableCreationModal');
                        const targetEl = document.getElementById('the-response');
                        targetEl.innerHTML = response.message;
                    }
                } else {
                    openModal('tableCreationModal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = response.message;
                }
            };
        });

        // Save SQL button functionality
        document.getElementById('save-sql').addEventListener('click', function () {
            var tableName = document.getElementById('table-name-input').value;
            var sql = document.getElementById('sqlCode').innerText;
            var postData = {
                tableName: tableName,
                sql: sql
            };
            var xhr = new XMLHttpRequest();
            var targetUrl = '<?= BASE_URL ?>vtl_gen/saveSqlDataTableCreationScript';
            xhr.open('POST', targetUrl, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify(postData));
            xhr.onload = function () {
                if (xhr.status === 200 && xhr.responseText) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        openModal('tableCreationModal');
                        const targetEl = document.getElementById('the-response');
                        targetEl.innerHTML = response.message;
                    } else {
                        openModal('tableCreationModal');
                        const targetEl = document.getElementById('the-response');
                        targetEl.innerHTML = response.message;
                    }
                } else {
                    openModal('tableCreationModal');
                    const targetEl = document.getElementById('the-response');
                    targetEl.innerHTML = response.message;
                }
            };
        });

        // Use Existing SQL button functionality
        document.getElementById('use-existing-sql').addEventListener('click', function () {
            isSqlLoaded = false;
            var input = document.createElement('input');
            input.type = 'file';
            input.accept = '.sql';
            input.onchange = function (event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('sqlCode').innerText = e.target.result;
                    Prism.highlightAll(); // Re-highlight the code block
                    isSqlLoaded = true;
                };
                reader.readAsText(file);
            };
            input.click();
        });

    });

</script>
<script src="<?= BASE_URL ?>vtl_gen_module/js/prism.js"></script>
<script src="<?= BASE_URL ?>js/app.js"></script>
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

    .custom-button-cross {
        color: #7b04d6;
        font-size: 24px;
        cursor: pointer;
    }

    .custom-button-cross:hover {
        color: #37035c;
    }


</style>
