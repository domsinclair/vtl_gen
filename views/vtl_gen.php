<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
<h1 class="text-center">Vtl Data Generator</h1>
<?php //echo "<pre>"; var_dump($images); echo "</pre>"; ?>
<div class="flex">
    <?php echo anchor('vtl_gen/createData', 'Create Data', array("class" => "button")); ?>
    <?php echo anchor('vtl_gen/deleteData', 'Delete Data', array("class" => "button")); ?>
    <?php echo anchor('vtl_gen/createIndex', 'Create Index', array("class" => "button")); ?>
    <?php echo anchor('vtl_gen/deleteIndex', 'Delete Index', array("class" => "button")); ?>
    <?php echo anchor('vtl_gen/export', 'Export Database', array("class" => "button")); ?>
</div>

<section>
    <div class="container">
        <div><?php echo $markdownIntro; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>

<section>
    <div class="container">
        <div><?php echo $markdownCreateData; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<section>
    <h4 class="text-center">Create Data Gallery</h4>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[0]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[1]?>" >
    </div>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[2]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[3]?>" >
    </div>
    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div><?php echo $markdownDeleteData; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<section>
    <h4 class="text-center">Delete Data Gallery</h4>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[7]?>" >
    </div>
    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div><?php echo $markdownCreateIndex; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<section>
    <h4 class="text-center">Create Index Gallery</h4>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[4]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[5]?>" >
    </div>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[6]?>" >
    </div>
    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>

<section>
    <div class="container">
        <div><?php echo $markdownDeleteIndex; ?></div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
    </div>
</section>
<section>
    <h4 class="text-center">Delete Index Gallery</h4>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[8]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[9]?>" >
    </div>
    <div class = "container flex">
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[10]?>" >
        <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[11]?>" >
    </div>
    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div><?php echo $markdownExport; ?></div>
    </div>
</section>

<section>
    <h4 class="text-center">Export Database Gallery</h4>
    <div class = "container flex">
    <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[12]?>" >
    <img height = "400px" width = "400px" src="<?= BASE_URL.'vtl_gen_module/help/images/'.$images[13]?>" >
    </div>
</section>
</body>
</html>

<style>
    .flex{
        gap: 20px
    }
</style>