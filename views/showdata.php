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
    <script type="text/javascript" src="<?= BASE_URL ?>vtl_gen_module/js/luxon.min.js"
    "></script>
    <title>Vtl_Generator_ShowData</title>
</head>
<body>
<h2 class="container, text-center"><?= $headline ?></h2>
<section>
    <div class="container">
        <div class="flex" style="margin-bottom: 15px">
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
        </div>
        <div id="datatable"></div
    </div>

</section>


<script>

    var tableData = <?php echo json_encode($rows); ?>;
    var noDataMessage = "<?= $noDataMessage ?>";

    document.addEventListener('DOMContentLoaded', function () {
        // Date formatter function
        // Date formatter function
        function dateFormatter(cell, formatterParams, onRendered) {
            return cell.getValue(); // Return the value without any formatting
        }


        // Function to determine if a string is a valid ISO date or datetime
        function isISODateString(value) {
            var dateTime = luxon.DateTime.fromISO(value);
            return dateTime.isValid;
        }

        // Generate columns dynamically based on the first row of data
        var columns = Object.keys(tableData[0] || {}).map(field => {
            return {
                title: field.charAt(0).toUpperCase() + field.slice(1),
                field: field,
                sorter: isISODateString(tableData[0][field]) ? "date" : "string",
                formatter: isISODateString(tableData[0][field]) ? dateFormatter : undefined
            };
        });

        // Create Tabulator table
        var table = new Tabulator("#datatable", {
            data: tableData,
            columns: columns,
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: true,
            paginationSize: 20,
            // paginationSizeSelector: [10, 20, 30, 40],
            movableColumns: true,
            paginationCounter: "pages",
            autoColumns: false, // We handle columns manually
            placeholder: noDataMessage
        });
    });
</script>
</body>
</html>

