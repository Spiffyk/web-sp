<div class="userform">
    <?php
    $session = Session::getInstance();

    if ($session->isActive()) {
        ?>

        Přihlášen jako <em><?php echo $session->getUser()->getName() ?></em>.<br />

        <?php
    } else {
        if (empty($_POST["username"])) {
            $username = "";
        } else {
            $username = $_POST["username"];
        }
        ?>

        <h2>Přihlášení</h2>
        <form method="POST">
            <input type="hidden" name="cmd" value="login">

            <label>Uživatelské jméno<br /><input type="text" name="username" value="<?php echo $username ?>"></label><br /><br />
            <label>Heslo<br /><input type="password" name="password"></label><br /><br />
            <input type="submit" value="Přihlásit">
        </form>

        <?php
    }
    ?>
</div>
