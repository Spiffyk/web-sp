<h2>Moje příspěvky</h2>

<?php
const ARTICLES_PER_PAGE = 20;

$session = Session::getInstance();

if ($session->getGroup()->hasPermission(Permissions::ARTICLE_CREATE)) {
    if (empty($_GET["page"])) {
        $current_page = 0;
    } else {
        $current_page = $_GET["page"];
    }

    $no_of_articles = Article::dao_countByAuthor($session->getUser());

    if ($no_of_articles == 0) {
        ?> Dosud jste nenahráli žádný příspěvek. Můžete to napravit <a href="?action=article-edit">zde</a>! <?php
    } else {
        $no_of_pages = ceil($no_of_articles / ARTICLES_PER_PAGE);

        $articles = Article::dao_getNewestByAuthor(
            $session->getUser(), ARTICLES_PER_PAGE, $current_page * ARTICLES_PER_PAGE);

        ?>

        <table>
            <colgroup>
                <col style="width: 1%">
                <col>
                <col style="width: 1%">
                <col style="width: 1%">
                <col style="width: 1%">
                <col style="width: 1%">
            </colgroup>
            <thead>
                <tr>
                    <td>Titulek</td>
                    <td>Abstrakt</td>
                    <td>Stav</td>
                    <td>Recenze</td>
                    <td>Stáhnout</td>
                    <td>Upravit</td>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($articles as $article) {
                    ?>

                    <tr>
                        <td><?php echo $article->getTitle(); ?></td>
                        <td class="wrap"><?php echo $article->getAbstractHtml(); ?></td>
                        <td>
                            <?php
                                switch($article->getState()) {
                                    case Article::STATE_AWAITING_REVIEW:
                                        echo "Čeká";
                                        break;
                                    case Article::STATE_ACCEPTED:
                                        echo "Přijat";
                                        break;
                                    case Article::STATE_REJECTED:
                                        echo "Zamítnut";
                                        break;
                                    default:
                                        echo "N/A (chyba)";
                                }
                            ?>
                        </td>
                        <td><a href="?action=article-reviews&article=<?php echo $article->getId(); ?>">Recenze</a></td>
                        <td><a href="/<?php echo $article->getFile(); ?>">Stáhnout</a></td>
                        <td><a href="?action=article-edit&article=<?php echo $article->getId(); ?>">Upravit</a></td>
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
    ?> Nemáte oprávnění k vytváření příspěvků. <?php
}
