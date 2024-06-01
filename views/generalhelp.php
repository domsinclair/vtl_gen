<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_URL ?>vtl_gen_module/css/vtl.css">
    <title>Vtl_Generator_Customise Fake Data</title>
</head>
<body>
<h2 class="text-center"><?= $headline ?></h2>
<section>
    <div class="container">
        <div class="flex">
            <?php echo anchor('vtl_gen', 'Back', array("class" => "button")); ?>
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
</body>
</html>
