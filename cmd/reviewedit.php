<?php
$fn = function() {
    $messenger = Messenger::getInstance();
    $session = Session::getInstance();
    $group = $session->getGroup();
    $user = $session->getUser();

    $article = Article::dao_getById($_POST["article"]);

    if (!$group->hasPermission(Permissions::ARTICLE_REVIEW)) {
        $messenger->message(Messenger::TYPE_ERROR, "Nemáte oprávnění k recenzování příspěvků!");
        return false;
    }

    if (empty($_POST["proposal"])) {
        $messenger->message(Messenger::TYPE_ERROR, "Návrh musí být vybrán!");
        return false;
    }

    $review = Review::dao_getForArticleByReviewer($article, $user);

    if (empty($review)) {
        $isNew = true;
        $review = new Review();
        $review->setArticle($article);
        $review->setAuthor($user);
    } else {
        $isNew = false;
    }

    $review->setCreated(new DateTime());
    $review->setContent($_POST["content"]);
    switch ($_POST["proposal"]) {
        case "accept":
            $review->setProposal(Review::PROPOSAL_ACCEPT);
            break;
        case "edit":
            $review->setProposal(Review::PROPOSAL_EDIT);
            break;
        case "reject":
            $review->setProposal(Review::PROPOSAL_REJECT);
            break;
        default:
            $messenger->message(Messenger::TYPE_ERROR, "Chybný návrh!");
            return false;
    }

    if ($isNew) {
        $review->dao_create();
    } else {
        $review->dao_update();
    }

    $messenger->message(Messenger::TYPE_SUCCESS, "Vaše recenze byla uložena.");
    return true;
};

$review_edit_complete = $fn();
