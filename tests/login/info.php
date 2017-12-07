<!doctype html>
<html>
    <head>
        <title>Login form</title>
    </head>
    <body>
        <?php
            $user = Session::getInstance()->getUser();
        ?>
        <h1>User</h1>
        <b>ID:</b> <?php echo $user->getId() ?><br />
        <b>User:</b> <?php echo $user->getName() ?><br />
        <b>Password:</b> hashed<br />
        <b>E-mail:</b> <?php echo $user->getEmail() ?><br />
        <a href="login.php?action=logout">Log out</a>
    </body>
</html>
