<?php
if (isset($_POST["cmd"])) {
    $cmd = $_POST["cmd"];
} else if (isset($_GET["cmd"])) {
    $cmd = $_GET["cmd"];
} else {
    $cmd = null;
}

switch($cmd) {
    case "login":
        require_once __DIR__ . "/cmd/login.php";
        break;
    case "logout":
        require_once __DIR__ . "/cmd/logout.php";
        break;
    case "register":
        require_once __DIR__ . "/cmd/register.php";
        break;
    case "user-accept":
        require_once __DIR__ . "/cmd/userapprove.php";
        break;
    case "user-reject":
        require_once __DIR__ . "/cmd/userreject.php";
        break;
    case "article-edit":
        require_once __DIR__ . "/cmd/articleedit.php";
        break;
    case "review-edit":
        require_once __DIR__ . "/cmd/reviewedit.php";
        break;
}
