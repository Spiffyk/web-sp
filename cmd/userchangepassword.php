<?php
$fn = function() {
    $messenger = Messenger::getInstance();
    $session = Session::getInstance();
    $user = $session->getUser();

    if (!$session->isActive()) {
        $messenger->message(Messenger::TYPE_ERROR, "Pro změnu hesla je nutné být přihlášen.");
        return;
    }

    $old_pwd = $_POST["old-password"];
    $new_pwd = $_POST["new-password"];
    $new_pwd_confirm = $_POST["new-password-confirm"];

    if (empty($old_pwd)) {
        $messenger->message(Messenger::TYPE_ERROR, "Staré heslo musí být vyplněné.");
        return;
    }

    if (strlen($new_pwd) < 8) {
        $messenger->message(Messenger::TYPE_ERROR, "Nové heslo musí být alespoň 8 znaků dlouhé.");
        return;
    }

    if ($new_pwd != $new_pwd_confirm) {
        $messenger->message(Messenger::TYPE_ERROR, "Nová hesla se musí shodovat.");
        return;
    }

    if (!$session->checkPassword($user, $old_pwd)) {
        $messenger->message(Messenger::TYPE_ERROR, "Nesprávné staré heslo.");
        return;
    }

    $user->setPasswordhash(password_hash($new_pwd, PASSWORD_BCRYPT));
    $user->dao_update();

    $messenger->message(Messenger::TYPE_SUCCESS, "Heslo bylo změněno.");
};

$fn();
