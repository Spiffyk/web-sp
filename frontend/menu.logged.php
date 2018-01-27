<?php
$session = Session::getInstance();
?>

<ul>
    <li class="username-display"><strong><?php echo Session::getInstance()->getUser()->getName(); ?></strong></li>
    <?php
    if ($session->getGroup()->hasPermission("config")) {
        ?> <li class="configuration-link"><a href="?action=sysconf">Systémová nastavení</a></li> <?php
    }
    ?>
    <li class="userarticles-link"><a href="?action=userarticles">Moje články</a></li>
    <li class="logout-link"><a href="?cmd=logout">Odhlásit se</a></li>
</ul>
