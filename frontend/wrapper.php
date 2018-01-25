<!doctype html>
<html>
    <head>
        <title>Templejt</title>
        <meta charset="UTF-8">

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"
                integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
                crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="titlebar">
            <?php echo Config::getInstance()->get("pagetitle"); ?>
        </div>
        <div id="menubar">
            <?php include __DIR__ . "/menu.php"; ?>
        </div>
        <div id="content">
            <?php include __DIR__ . "/content.php"; ?>
        </div>
        <div id="footer">
            <?php include __DIR__ . "/footer.php"; ?>
        </div>
    </body>
</html>