<?php

$fn = function(): bool {
    $messenger = Messenger::getInstance();

    if (empty($_POST["username"]) ||
        empty($_POST["email"]) ||
        empty($_POST["password"]) ||
        empty($_POST["password-confirm"])) {

        $messenger->message(Messenger::TYPE_ERROR, "Všechna pole musí být vyplněna.");
        return false;
    }

    $username = strtolower($_POST["username"]);
    $email = strtolower($_POST["email"]);
    $password = $_POST["password"];
    $password_confirm = $_POST["password-confirm"];

    $all_good = true;

    if (preg_match("/[a-z]+/", $username) !== 1) {
        $all_good = false;
        $messenger->message(Messenger::TYPE_ERROR, "Uživatelské jméno musí obsahovat pouze písmena.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $all_good = false;
        $messenger->message(Messenger::TYPE_ERROR, "E-mail musí být ve správném tvaru.");
    }

    if ($password != $password_confirm) {
        $all_good = false;
        $messenger->message(Messenger::TYPE_ERROR, "Hesla se musí v obou polích shodovat.");
    }

    if (strlen($password) < 8) {
        $all_good = false;
        $messenger->message(Messenger::TYPE_ERROR, "Heslo musí být alespoň 8 znaků dlouhé.");
    }

    if (!$all_good) {
        return false;
    }

    if (!empty(User::dao_getByName($username))) {
        $messenger->message(Messenger::TYPE_ERROR, "Vybrané uživatelské jméno není dostupné.");
        return false;
    }

    $pdo = Database::getInstance()->getPdo();

    $user = new User();
    $user->setName($username);
    $user->setEmail($email);
    $user->setGroup(UserGroup::get(UserGroup::GROUP_UNVERIFIED));
    $user->setPasswordhash(password_hash($password, PASSWORD_BCRYPT));

    $approval = new UserApproval();
    $approval->setState(UserApproval::STATE_OPEN);
    $approval->setUser($user);
    $approval->setOpened(new DateTime());
    $approval->setClosed(null);

    $pdo->beginTransaction();
    $user->dao_create();
    $approval->dao_create();
    $pdo->commit();

    $messenger->message(
        Messenger::TYPE_SUCCESS,
        "Registrace proběhla úspěšně.",
        "Nyní se čeká na schválení správcem.");
    return true;
};

$registration_complete = $fn();
