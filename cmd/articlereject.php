<?php
$group = Session::getInstance()->getGroup();

if ($group->hasPermission(Permissions::ARTICLE_APPROVAL)) {
    $article = Article::dao_getById($_POST["article"]);
    $article->setState(Article::STATE_REJECTED);
    $article->dao_update();

    Messenger::getInstance()->message(Messenger::TYPE_SUCCESS, "Příspěvek byl zamítnut.");
}
