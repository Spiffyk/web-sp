<?php
$session = Session::getInstance();
$group = $session->getGroup();
?>

<ul>
    <li><strong><?php echo Session::getInstance()->getUser()->getName(); ?></strong></li>
    <?php
    if ($group->hasPermission(Permissions::USER_APPROVAL)) {
        ?>

        <li>
            <a href="?action=user-approval">Uživatelé ke schválení</a>
            <?php
                $count = UserApproval::dao_countOpen();
                if ($count > 0) {
                    echo "(" . $count . ")";
                }
            ?>
        </li>

        <?php
    }

    if ($group->hasPermission(Permissions::ARTICLE_APPROVAL)) {
        ?>

        <li>
            <a href="?action=article-approval">Články ke schválení</a>
            <?php
            $count = Article::dao_countWaiting();
            if ($count > 0) {
                echo "(" . $count . ")";
            }
            ?>
        </li>

        <?php
    }

    if ($group->hasPermission(Permissions::ARTICLE_CREATE)) {
        ?> <li><a href="?action=user-articles">Moje příspěvky</a></li> <?php
        ?> <li><a href="?action=article-edit">Vytvořit příspěvek</a></li> <?php
    }
    ?>
    <li><a href="?cmd=logout">Odhlásit se</a></li>
</ul>
