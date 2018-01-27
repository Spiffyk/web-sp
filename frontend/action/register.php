<div class="userform">
    <?php
    $session = Session::getInstance();

    if ($session->isActive()) {
        ?>

        Přihlášen jako <em><?php echo $session->getUser()->getName() ?></em>.<br />
        Registrace je povolena jen nepřihlášeným uživatelům.

        <?php
    } else {
        ?>

        <h2>Registrace</h2>

        <form method="post">
            <input type="hidden" name="cmd" value="register">

            <label>Uživatelské jméno<br /><input type="text" name="username"></label><br /><br />
            <label>E-mail<br /><input type="email" name="email"></label><br /><br />
            <label>Heslo<br /><input type="password" name="password"></label><br /><br />
            <label>Heslo (potvrzení)<br /><input type="password" name="password-confirm"></label><br /><br />

            <input type="submit" value="Odeslat registraci ke schválení">

        </form>

        <?php
    }
    ?>
</div>
