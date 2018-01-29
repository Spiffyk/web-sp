<h2>Nahrát příspěvek</h2>

<?php
$session = Session::getInstance();
$group = $session->getGroup();

$title = $_POST["title"];
$htmlabstract = htmlspecialchars($_POST["abstract"]);
$abstract = str_replace("\n", "<br />", $htmlabstract);

if (empty($_GET["article"])) {
    $isNew = true;
    $article_id = "new";
} else {
    $isNew = false;
    $article_id = $_GET["article"];
    $article = Article::dao_getById($article_id);

    if (empty($title)) {
        $title = $article->getTitle();
    }

    if (empty($abstract)) {
        $abstract = $article->getAbstractHtml();
    }
}

if ($group->hasPermission(Permissions::ARTICLE_CREATE)) {
    ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="cmd" value="article-edit">
        <input type="hidden" name="article-id" value="<?php echo $article_id; ?>">

        <label>Titulek<br /><input type="text" name="title" size="100" value="<?php echo $title; ?>"></label>
        <br /><br />
        <label>Abstrakt<br /><textarea name="abstract"><?php echo $abstract; ?></textarea></label>
        <br /><br />
        <label>
            PDF Soubor

            <?php
            if (!$isNew) {
                echo "- <em><a href=\"/" . $article->getFile() . "\">stávající</a> bude <strong>přepsán</strong>, pokud bude vybrán nový</em>";
            }
            ?>

            <br />
            <input type="file" name="file" accept="application/pdf"> (max. <?php echo Article::PDF_MAX_MB ?> MB)</label>
        <br /><br />
        <input type="submit" value="Uložit">

        <?php
        if (!$isNew) {
            echo "- <em>příspěvek bude vrácen do stavu čekání na schválení</em>";
        }
        ?>
    </form>

    <?php
} else {
    ?> Nemáte oprávnění k tvorbě/úpravě článků. <?php
}
