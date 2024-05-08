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
<h2 class="text-center">Vtl Data Generator: Show Data</h2>
<section>
    <div class="container">
        <div class="flex" style="margin-bottom: 15px">
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
        </div>
        <section>
            <div>
                <?php echo Pagination::display($pagination_data); ?>
            </div>
        </section>


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
            if (count($rows) > 9) {
                unset($pagination_data['include_showing_statement']);
                echo Pagination::display($pagination_data);
            }
        } else {
            echo "There is no data in the table that you selected.";
        }
        ?>
    </div>
</section>
</body>
</html>

