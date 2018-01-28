<?php

$fn = function () {
    $session = Session::getInstance();
    $messenger = Messenger::getInstance();

    if ($session->getGroup()->hasPermission(Permissions::USER_APPROVAL)) {
        $approval = UserApproval::dao_getById($_POST["approval"]);
        $user = $approval->getUser();

        switch ($_POST["role"]) {
            case "reviewer":
                $group_id = UserGroup::GROUP_REVIEWER;
                break;
            case "author":
                $group_id = UserGroup::GROUP_AUTHOR;
                break;
            case "admin":
                $group_id = UserGroup::GROUP_ADMIN;
                break;
            case "reader":
            default:
                $group_id = UserGroup::GROUP_READER;
        }

        $group = UserGroup::get($group_id);
        $user->setGroup($group);
        $approval->setClosed(new DateTime());
        $approval->setState(UserApproval::STATE_ACCEPTED);

        Database::getInstance()->getPdo()->beginTransaction();
        $approval->dao_update();
        $user->dao_update();
        Database::getInstance()->getPdo()->commit();

        $messenger->message(
            Messenger::TYPE_SUCCESS,
            "Uživatel <em>" . $user->getName() . "</em> (ID: " . $user->getId() . ") byl schválen!",
            "Je nyní členem skupiny <em>" . $group->getName() . "</em> (ID: " . $group->getId() . ").");
    } else {
        $messenger->message(Messenger::TYPE_ERROR, "Nemáte oprávnění ke schvalování uživatelů!");
    }
};

$fn();
