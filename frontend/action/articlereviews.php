<?php
$group = Session::getInstance()->getGroup();

if ($group->hasPermission(Permissions::ARTICLE_READ)) {
    $article = Article::dao_getById($_GET["article"]);
    ?>

    <h2>Příspěvek <em><?php echo $article->getTitle() ?></em></h2>

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

    <h3>
        Recenze

        <?php
            if ($group->hasPermission(Permissions::ARTICLE_REVIEW)) {
                echo "(<a href=\"?action=review-edit&article=" . $article->getId() . "\">vytvořit / upravit</a>)";
            }
        ?>
    </h3>

    <?php
    $reviews = Review::dao_getForArticle($article);

    if (sizeof($reviews) > 0) {
        ?>

        <table>
            <colgroup>
                <col>
                <col style="width: 1%">
                <col style="width: 1%">
                <col style="width: 1%">
            </colgroup>
            <thead>
            <tr>
                <td>Text recenze</td>
                <td>Recenzent</td>
                <td>Datum</td>
                <td>Návrh</td>
            </tr>
            </thead>
            <tbody>

                <?php
                foreach ($reviews as $review) {
                    ?>

                    <tr>
                        <td class="wrap"><?php echo $review->getContentHtml(); ?></td>
                        <td><?php echo $review->getAuthor()->getName(); ?></td>
                        <td><?php echo $review->getCreated()->format("Y-m-d"); ?></td>
                        <td>
                            <?php
                            switch ($review->getProposal()) {
                                case Review::PROPOSAL_REJECT:
                                    echo "Zamítnout";
                                    break;
                                case Review::PROPOSAL_ACCEPT:
                                    echo "Přijmout";
                                    break;
                                case Review::PROPOSAL_EDIT:
                                    echo "Upravit";
                                    break;
                                default:
                                    echo "N/A (Chyba)";
                            }
                            ?>
                        </td>
                    </tr>

                    <?php
                }
                ?>

            </tbody>
        </table>

        <?php
    } else {
        ?> Tento příspěvek dosud nemá žádné recenze. <?php
    }

    if ($article->getState() == Article::STATE_AWAITING_REVIEW &&
        $group->hasPermission(Permissions::ARTICLE_APPROVAL)) {

        ?>

        <h3>Schválení</h3>

        <div style="text-align: left;">
            <form method="post" style="display: inline-block;">
                <input type="hidden" name="cmd" value="article-approve">
                <input type="hidden" name="article" value="<?php echo $article->getId(); ?>">
                <input type="submit" value="Schválit">
            </form>

            <form method="post" style="display: inline-block;">
                <input type="hidden" name="cmd" value="article-reject">
                <input type="hidden" name="article" value="<?php echo $article->getId(); ?>">
                <input type="submit" value="Zamítnout">
            </form>
        </div>

        <?php
    }
} else {
    ?> Nemáte oprávnění ke čtení článků a jejich recenzí! <?php
}
