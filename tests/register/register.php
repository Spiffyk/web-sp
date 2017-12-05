<?php
require_once __DIR__."/../../main.php";

$user = new User();
$user->setName($_POST["name"]);
$user->setPasswordhash(password_hash($_POST["password"], PASSWORD_BCRYPT));
$user->setEmail($_POST["email"]);
$user->setGroup(UserGroup::get(1));

$user->dao_create();
?>
<!doctype html>
<html>
    <head>
        <title>Registered user</title>
    </head>
    <body>
        <h1>User created</h1>
        <b>ID:</b> <?php echo $user->getId() ?><br />
        <b>User:</b> <?php echo $user->getName() ?><br />
        <b>Password:</b> hashed<br />
        <b>E-mail:</b> <?php echo $user->getEmail() ?><br />
    </body>
</html>
