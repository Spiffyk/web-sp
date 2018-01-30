<h2>Nastavení profilu</h2>
<?php
$session = Session::getInstance();
$user = $session->getUser();

if ($session->isActive()) {
    ?>

    <div class="userform">

        <h3>E-mail</h3>
        <form method="post">
            <input type="hidden" name="cmd" value="user-change-email">

            <label>E-mail<br /><input type="email" name="email" value="<?php echo $user->getEmail() ?>"></label>
            <br /><br />
            <input type="submit" value="Změnit e-mail">
        </form>

        <h3>Heslo</h3>
        <form method="post">
            <input type="hidden" name="cmd" value="user-change-password">

            <label>Staré heslo (pro ověření)<br /><input type="password" name="old-password"></label>
            <br /><br />
            <label>Nové heslo<br /><input type="password" name="new-password"></label>
            <br /><br />
            <label>Nové heslo (potvrzení)<br /><input type="password" name="new-password-confirm"></label>
            <br /><br />
            <input type="submit" value="Změnit heslo">
        </form>

    </div>

    <?php
} else {
    ?> Pro úpravu profilu je nutné být přihlášen. <?php
}
