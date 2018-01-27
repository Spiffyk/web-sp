<?php
$pagetitle = Config::getInstance()->get("pagetitle");
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo $pagetitle; ?></title>
        <link rel="stylesheet" href="/frontend/css/main.css">
        <link rel="stylesheet" href="/frontend/css/titlebar.css">
        <link rel="stylesheet" href="/frontend/css/menubar.css">
        <link rel="stylesheet" href="/frontend/css/messenger.css">
        <link rel="stylesheet" href="/frontend/css/content.css">
        <link rel="stylesheet" href="/frontend/css/footer.css">

<!--        <script src="https://code.jquery.com/jquery-3.2.1.min.js"-->
<!--                integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="-->
<!--                crossorigin="anonymous"></script>-->
    </head>
    <body>
        <div id="titlebar">
            <h1><a href="/"><?php echo $pagetitle; ?></a></h1>
        </div>
        <div id="menubar">
            <?php include __DIR__ . "/menu.php"; ?>
        </div>
        <div id="messenger">
            <?php include __DIR__ . "/messenger.php" ?>
        </div>
        <div id="content">
            <?php include __DIR__ . "/content.php"; ?>
        </div>
        <div id="footer">
            <?php include __DIR__ . "/footer.php"; ?>
        </div>
    </body>
</html>