<!doctype html>
<html>
    <head>
        <title>Templejt</title>
        <meta charset="UTF-8">

        <script src="//cdn.ckeditor.com/4.7.3/basic/ckeditor.js"></script>
    </head>
    <body>
        <?php
            if (empty($content)) {
                echo "Nothing to show.";
            } else {
                echo $content;
            }
        ?>
    </body>
</html>