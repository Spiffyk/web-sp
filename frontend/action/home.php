<h2>Vítejte v konferenci</h2>

<?php
if (Session::getInstance()->getGroup()->hasPermission(Permissions::ARTICLE_READ)) {
    require_once __DIR__ . "/home/articles.php";
} else {
    require_once __DIR__ . "/home/welcome.php";
}
?>
