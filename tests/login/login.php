<!doctype html>
<html>
    <head>
        <title>Login form</title>
    </head>
    <body>
        <?php
            if (empty($_POST["name"]) || empty($_POST["password"])) {
                echo "Wrong input.";
            } else {
                $user = User::dao_getByName($_POST["name"]);
                if ($user == null) {
                    password_verify("", "");
                    echo "Wrong.";
                } else {
                    if (password_verify($_POST["password"], $user->getPasswordhash())) {
                        Session::getInstance()->start($user);
                        echo "Logged in as " . $user->getName() . "!";
                    } else {
                        echo "Wrong.";
                    }
                }
            }
        ?>

        <a href="login.php">Back.</a>
    </body>
</html>