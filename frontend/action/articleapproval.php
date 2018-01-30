<h2>Příspěvky ke schválení</h2>

<?php

const ARTICLES_PER_PAGE = 20;

$group = Session::getInstance()->getGroup();

$canApprove = $group->hasPermission(Permissions::ARTICLE_APPROVAL);

if ($canApprove ||
    $group->hasPermission(Permissions::ARTICLE_REVIEW)) {

    if (empty($_GET["page"])) {
        $current_page = 0;
    } else {
        $current_page = $_GET["page"];
    }

    $no_of_articles = Article::dao_countWaiting();

    if ($no_of_articles == 0) {
        ?> Zatím zde není žádný příspěvek. <?php
    } else {
        $no_of_pages = ceil($no_of_articles / ARTICLES_PER_PAGE);

        $articles = Article::dao_getNewestWaiting(ARTICLES_PER_PAGE, $current_page * ARTICLES_PER_PAGE);

        ?>

        <table>
            <colgroup>
                <col style="width: 1%">
                <col style="width: 1%">
                <col>
                <col style="width: 1%">
                <col style="width: 1%">
            </colgroup>
            <thead>
            <tr>
                <td>Titulek</td>
                <td>Autor</td>
                <td>Abstrakt</td>
                <td>Datum</td>
                <td>
                    Recenze

                    <?php
                    if ($canApprove) {
                        echo "/ Schválení";
                    }
                    ?>
                </td>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($articles as $article) {
                ?>

                <tr>
                    <td><?php echo $article->getTitle(); ?></td>
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
                    <td>
                        <a href="?action=article-reviews&article=<?php echo $article->getId(); ?>">
                            Recenze

                            <?php
                            if ($canApprove) {
                                echo "/ Schválení";
                            }
                            ?>
                        </a>
                    </td>
                </tr>

                <?php
            }
            ?>

            </tbody>
        </table>

        <div class="pager">

            <?php
            for ($i = 0; $i < $no_of_pages; $i++) {
                if ($i == $current_page) {
                    echo "<strong>" . ($i + 1) . "</strong> ";
                } else {
                    echo "<a href=\"?action=article-approval&page=" . $i . "\">" . ($i + 1) . "</a> ";
                }
            }
            ?>

        </div>

        <?php
    }

} else {
    ?> Nemáte oprávnění ke schvalování příspěvků. <?php
}

?>
