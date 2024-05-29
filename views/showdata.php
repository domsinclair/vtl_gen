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
    <title>Vtl_Generator_ShowData</title>
</head>
<body>
<h2 class="text-center"><?= $headline ?></h2>
<section>
    <div class="container">
        <div class="flex" style="margin-bottom: 15px">
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
        </div>
        <div id="datatable"></div


</section>
<script>
    var tableData = <?php echo json_encode($rows); ?>;

    document.addEventListener('DOMContentLoaded', function () {
        var table = new Tabulator("#datatable", {
            data: tableData,
            autoColumns: true,
            layout: "fitColumns",
            pagination: true,
            paginationSize: 20
        });
    });
</script>
</body>
</html>

