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
    default:
        echo "<em>Undefined action <strong>" . htmlspecialchars($action) . "</strong>!</em>";
}
