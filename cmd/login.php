<?php
$fn = function () {
    $session = Session::getInstance();
    $messenger = Messenger::getInstance();

    if ($session->isActive()) {
        die("There is already an active session. Aborting process.");
    }

    if (empty($_POST["username"])) {
        $messenger->message(Messenger::TYPE_ERROR, "Uživatelské jméno musí být vyplněno.");
        return;
    }

    if (empty($_POST["password"])) {
        $messenger->message(Messenger::TYPE_ERROR, "Heslo musí být vyplněno.");
        return;
    }

    switch ($session->login(strtolower($_POST["username"]), $_POST["password"])) {
        case Session::LOGIN_SUCCESS:
            $messenger->message(Messenger::TYPE_SUCCESS, "Přihlášení bylo úspěšné.");
            break;
        case Session::LOGIN_WRONG_CREDENTIALS:
            $messenger->message(Messenger::TYPE_ERROR, "Nesprávné uživatelské jméno nebo heslo.");
            break;
        default:
            $messenger->message(Messenger::TYPE_ERROR, "Při přihlašování došlo k neznámé chybě.");
    }
};

$fn();
