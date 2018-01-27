<?php
$session = Session::getInstance();
$group = $session->getGroup();
?>

<ul>
    <li><strong><?php echo Session::getInstance()->getUser()->getName(); ?></strong></li>
    <?php
    if ($group->hasPermission("control_panel")) {
        ?> <li><a href="?action=cpanel">Ovládací panel</a></li> <?php
    }

    if ($group->hasPermission("user_approval")) {
        ?> <li><a href="?action=user-approval">Uživatelé ke schválení</a></li> <?php
    }

    if ($group->hasPermission("article_approval")) {
        ?> <li><a href="?action=article-approval">Články ke schválení</a></li> <?php
    }
    ?>
    <li><a href="?action=userarticles">Moje články</a></li>
    <li><a href="?cmd=logout">Odhlásit se</a></li>
</ul>
