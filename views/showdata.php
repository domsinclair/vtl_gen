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
<div>
    <?php echo Pagination::display($pagination_data); ?>
</div>


<?php
// Check if rows data exists and has rows
if (!empty($rows)) {
    ?>
    <table>
        <thead>
        <tr>
            <?php foreach (array_keys((array)$data['rows'][0]) as $header): ?>
                <th><?= $header ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['rows'] as $row): ?>
            <tr>
                <?php foreach ($row as $value): ?>
                    <td><?= $value ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


<?php
    if(count($rows)>9) {
        unset($pagination_data['include_showing_statement']);
        echo Pagination::display($pagination_data);
    }
} else {
    echo "There is no data in the table that you selected.";
}
?>

</body>
</html>
