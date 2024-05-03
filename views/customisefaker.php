<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/vtl.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/prism.css">
    <title>Vtl_Generator_Customise Fake Data</title>
</head>
<body>
<h2 class="text-center">Vtl Data Generator: Customising Fake Data</h2>
<div class="flex">
    <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
</div>
<section>
    <div class="container">
        <div><?php echo $markdownCustomise; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<script src="<?= BASE_URL ?>vtl_gen_module/js/prism.js"></script>
</body>
</html>

