<div class="userform">
    <?php
    global $registration_complete;

    $session = Session::getInstance();

    if (!isset($registration_complete) || !$registration_complete) {
        if ($session->isActive()) {
            ?>

            Přihlášen jako <em><?php echo $session->getUser()->getName() ?></em>.<br/>
            Registrace je povolena jen nepřihlášeným uživatelům.

            <?php
        } else {
            if (empty($_POST["username"])) {
                $username = "";
            } else {
                $username = htmlspecialchars($_POST["username"]);
            }

            if (empty($_POST["email"])) {
                $email = "";
            } else {
                $email = htmlspecialchars($_POST["email"]);
            }

            ?>

            <h2>Registrace</h2>

            <form method="post">
                <input type="hidden" name="cmd" value="register">

                <label>Uživatelské jméno<br/><input type="text" name="username" value="<?php echo $username ?>"></label><br/><br/>
                <label>E-mail<br/><input type="email" name="email" value="<?php echo $email ?>"></label><br/><br/>
                <label>Heslo<br/><input type="password" name="password"></label><br/><br/>
                <label>Heslo (potvrzení)<br/><input type="password" name="password-confirm"></label><br/><br/>

                <input type="submit" value="Odeslat registraci ke schválení">

            </form>

            <?php
        }
    }
    ?>
</div>
