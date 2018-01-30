<?php
$fn = function() {
    $messenger = Messenger::getInstance();
    $session = Session::getInstance();
    $user = $session->getUser();

    if (!$session->isActive()) {
        $messenger->message(Messenger::TYPE_ERROR, "Pro změnu e-mailu je nutné být přihlášen.");
        return;
    }

    $email = $_POST["email"];

    if (empty($email)) {
        $messenger->message(Messenger::TYPE_ERROR, "E-mail musí být vyplněn.");
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messenger->message(Messenger::TYPE_ERROR, "E-mail musí být ve správném tvaru.");
        return;
    }

    $userByEmail = User::dao_getUserByEmail($email);

    if ($userByEmail != null && $userByEmail->getId() != $user->getId()) {
        $messenger->message(Messenger::TYPE_ERROR, "Tento e-mail je již používán.");
        return;
    }

    $user->setEmail($email);
    $user->dao_update();

    $messenger->message(Messenger::TYPE_SUCCESS, "E-mail byl změněn.");
};

$fn();
