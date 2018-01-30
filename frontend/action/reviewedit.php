<h2>Recenze příspěvku</h2>

<?php
$session = Session::getInstance();
$group = $session->getGroup();

if ($group->hasPermission(Permissions::ARTICLE_REVIEW)) {
    $article = Article::dao_getById($_GET["article"]);
    $review = Review::dao_getForArticleByReviewer($article, $session->getUser());

    if (empty($review)) {
        $rcontent = $_POST["content"];
        $rprop = $_POST["proposal"];
    } else {
        $rcontent = $review->getContent();
        switch ($review->getProposal()) {
            case Review::PROPOSAL_ACCEPT:
                $rprop = "accept";
                break;
            case Review::PROPOSAL_REJECT:
                $rprop = "reject";
                break;
            case Review::PROPOSAL_EDIT:
                $rprop = "edit";
                break;
        }
    }

    ?>

    <table>
        <colgroup>
            <col style="width: 1%">
            <col>
            <col style="width: 1%">
            <col style="width: 1%">
            <col style="width: 1%">
        </colgroup>
        <thead>
        <tr>
            <td>Autor</td>
            <td>Abstrakt</td>
            <td>Datum</td>
            <td>Stáhnout PDF</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $article->getAuthor()->getName(); ?></td>
            <td class="wrap"><?php echo $article->getAbstractHtml(); ?></td>
            <td>

                <?php
                if (empty($article->getModified())) {
                    $date = $article->getCreated();
                } else {
                    $date = $article->getModified();
                }

                echo $date->format("Y-m-d");
                ?>

            </td>
            <td><a href="/<?php echo $article->getFile(); ?>">Stáhnout PDF</a></td>
        </tr>
        </tbody>
    </table>

    <?php
    global $review_edit_complete;
    if (!isset($review_edit_complete) || !$review_edit_complete) {
        ?>

        <h3>Moje recenze</h3>

        <form method="post">
            <input type="hidden" name="cmd" value="review-edit">
            <input type="hidden" name="article" value="<?php echo $article->getId(); ?>">

            <label>Text recenze<br/><textarea name="content"><?php echo $rcontent; ?></textarea></label>
            <br/><br/>
            <label>
                Návrh<br/>
                <select name="proposal">
                    <option></option>
                    <option value="accept" <?php echo ($rprop == "accept") ? "selected" : ""; ?>>Přijmout</option>
                    <option value="reject" <?php echo ($rprop == "reject") ? "selected" : ""; ?>>Zamítnout</option>
                    <option value="edit" <?php echo ($rprop == "edit") ? "selected" : ""; ?>>Upravit</option>
                </select>
            </label>
            <input type="submit" value="Odeslat">
        </form>

        <?php
    } else {
        ?> Recenze byla upravena. <?php
    }
} else {
    ?> Nemáte oprávnění k recenzování příspěvků. <?php
}
