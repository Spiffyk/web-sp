<?php
$group = Session::getInstance()->getGroup();

if ($group->hasPermission(Permissions::ARTICLE_APPROVAL)) {
    $article = Article::dao_getById($_POST["article"]);
    $article->setState(Article::STATE_ACCEPTED);
    $article->setModified(new DateTime());
    $article->dao_update();

    Messenger::getInstance()->message(Messenger::TYPE_SUCCESS, "Příspěvek byl schválen.");
}
