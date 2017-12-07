<?php
require_once __DIR__."/../main.php";
if (empty($_GET["action"])) {
    if (Session::getInstance()->isActive()) {
        require_once __DIR__."/login/info.php";
    } else {
        require_once __DIR__."/login/form.php";
    }
} else if ($_GET["action"] == "login") {
    require_once __DIR__."/login/login.php";
} else if ($_GET["action"] == "logout") {
    require_once __DIR__."/login/logout.php";
} else {
    die("Invalid action.");
}