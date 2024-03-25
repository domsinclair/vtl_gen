<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vtl_Generator</title>
</head>
<body>
<h1 class="text-center">Vtl Data Generator</h1>
<div class="flex" >
    <!--    --><?php //echo anchor('vtl_gen/showHelp', 'Show Vtl Generator Help', array("class" => "button")); ?>
    <?php echo anchor('vtl_gen/createData', 'Create Data', array("class" => "button")); ?>
    <?php echo anchor('vtl_gen/deleteData', 'Delete Data', array("class" => "button")); ?>
    <?php echo anchor('vtl_gen/createIndex', 'Create Index', array("class" => "button")); ?>
    <?php echo anchor('vtl_gen/deleteIndex', 'Delete Index', array("class" => "button")); ?>
    <?php echo anchor('vtl_gen', 'Export Database', array("class" => "button")); ?>

</div>

<section>
    <div class="container">
        <div> <?php echo $markdownIntro ?> </div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div> <?php echo $markdownCreateData ?> </div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div> <?php echo $markdownDeleteData ?> </div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div> <?php echo $markdownCreateIndex?> </div>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
</section>
<section>
    <div class="container">
        <div> <?php echo $markdownDeleteIndex ?> </div>

</section>
</body>
</html>
<style>
    .flex {
        display: flex;
        justify-content: center; /* Center items horizontally */
        align-items: center; /* Center items vertically */
        gap: 4px;
    }
</style>
