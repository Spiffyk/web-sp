<!doctype html>
<html>
    <head>
        <title>Logout</title>
    </head>
    <body>
        <?php
            Session::getInstance()->destroy();
        ?>
        Logged out. <a href="login.php">Back.</a>
    </body>
</html>