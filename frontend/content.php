<?php

if (empty($_GET["action"])) {
    $action = "home";
} else {
    $action = $_GET["action"];
}

switch($action) {
    case "home":
        require __DIR__ . "/action/home.php";
        break;
    case "login":
        require __DIR__ . "/action/login.php";
        break;
    case "register":
        require __DIR__ . "/action/register.php";
        break;
    case "user-approval":
        require __DIR__ . "/action/userapproval.php";
        break;
    case "article-edit":
        require __DIR__ . "/action/articleedit.php";
        break;
    case "user-articles":
        require __DIR__ . "/action/userarticles.php";
        break;
    case "article-approval":
        require __DIR__ . "/action/articleapproval.php";
        break;
    case "article-reviews":
        require __DIR__ . "/action/articlereviews.php";
        break;
    case "review-edit":
        require __DIR__ . "/action/reviewedit.php";
        break;
    case "user-self-edit":
        require __DIR__ . "/action/userselfedit.php";
        break;
    default:
        echo "<em>Undefined action <strong>" . htmlspecialchars($action) . "</strong>!</em>";
}
