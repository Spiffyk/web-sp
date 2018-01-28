<?php

$fn = function () {
    $session = Session::getInstance();
    $messenger = Messenger::getInstance();

    if ($session->getGroup()->hasPermission(Permissions::USER_APPROVAL)) {
        $approval = UserApproval::dao_getById($_POST["approval"]);
        $user = $approval->getUser();

        $approval->setClosed(new DateTime());
        $approval->setState(UserApproval::STATE_REJECTED);
        $approval->dao_update();

        $messenger->message(
            Messenger::TYPE_SUCCESS,
            "Uživatel <em>" . $user->getName() . "</em> (ID: " . $user->getId() . ") byl zamítnut.");
    } else {
        $messenger->message(Messenger::TYPE_ERROR, "Nemáte oprávnění k zamítání uživatelů!");
    }
};

$fn();
