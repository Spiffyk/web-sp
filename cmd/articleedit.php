<?php

$fn = function() {
    $session = Session::getInstance();
    $group = $session->getGroup();
    $messenger = Messenger::getInstance();

    if (!$group->hasPermission(Permissions::ARTICLE_CREATE)) {
        $messenger->message(Messenger::TYPE_ERROR, "K úpravě/tvorbě příspěvků nemáte oprávnění!");
        return;
    }

    if ($_POST["article-id"] == "new") {
        $isNew = true;
    } else {
        $isNew = false;
    }

    $all_good = true;

    if (empty($_POST["title"])) {
        $messenger->message(Messenger::TYPE_ERROR, "Titulek musí být vyplněn!");
        $all_good = false;
    }

    $fileUploaded = true;
    if (empty($_FILES["file"]["name"])) {
        $fileUploaded = false;
        if ($isNew) {
            $messenger->message(Messenger::TYPE_ERROR, "Je potřeba nahrát PDF soubor!");
            $all_good = false;
        }
    } else if (preg_match("/.*\.pdf\z/", $_FILES["file"]["name"]) !== 1) {
        $messenger->message(Messenger::TYPE_ERROR, "Soubor musí být typu PDF.");
        $all_good = false;
    } else if ($_FILES["file"]["size"] > (Article::PDF_MAX_MB * 1000000)) {
        $messenger->message(Messenger::TYPE_ERROR,
            "PDF soubor může mít velikost maximálně " . Article::PDF_MAX_MB . " MB.");
        $all_good = false;
    }

    if (!$all_good) {
        return;
    }

    if ($isNew) {
        $article = new Article();
        $article->setAuthor($session->getUser());
        $article->setCreated(new DateTime());
    } else {
        $article = Article::dao_getById($_POST["article-id"]);
        $article->setModified(new DateTime());

        if ($article->getAuthor()->getId() != $session->getUser()->getId()) {
            $messenger->message(Messenger::TYPE_ERROR, "Nelze upravovat cizí příspěvky!");
            return;
        }
    }

    $article->setState(Article::STATE_AWAITING_REVIEW);
    $article->setTitle($_POST["title"]);
    $article->setAbstract($_POST["abstract"]);

    Database::getInstance()->getPdo()->beginTransaction();
    if ($isNew) {
        $article->setFile("");
        $article->dao_create();
    }

    $file = Article::PDF_PATH_PREFIX . $article->getId() . ".pdf";

    if ($fileUploaded) {
        move_uploaded_file($_FILES["file"]["tmp_name"], $file);
    }

    $article->setFile($file);
    $article->dao_update();
    Database::getInstance()->getPdo()->commit();

    if ($isNew) {
        $messenger->message(Messenger::TYPE_SUCCESS, "Příspěvek byl úspěšně vytvořen.");
    } else {
        $messenger->message(Messenger::TYPE_SUCCESS, "Příspěvek byl úspěšně upraven.");
    }
};

$fn();
