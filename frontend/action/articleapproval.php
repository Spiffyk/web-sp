<h2>Příspěvky ke schválení</h2>

<?php

const ARTICLES_PER_PAGE = 20;

if (Session::getInstance()->getGroup()->hasPermission(Permissions::ARTICLE_APPROVAL)) {
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
                <td>Schválení</td>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($articles as $article) {
                ?>

                <tr>
                    <td><?php echo $article->getTitle(); ?></td>
                    <td><?php echo $article->getAuthor()->getName(); ?></td>
                    <td class="wrap"><?php echo $article->getAbstract(); ?></td>
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
                    <td><a href="?action=article-reviews&article=<?php echo $article->getId(); ?>">Schválení</a></td>
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
                    echo "<a href=\"?action=user-articles&page=" . $i . "\">" . ($i + 1) . "</a> ";
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
