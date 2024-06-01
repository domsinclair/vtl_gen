<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/vtl.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/prism.css">
    <title>Vtl_Generator_Points of Interest</title>
</head>
<body>
<h2 class="container, text-center"><?= $headline ?></h2>
<section>
    <div class="container">
        <div class="flex">
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
        </div>
        <section>
            <div class="container">
                <div><?php echo $markdownCustomise; ?></div>
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


