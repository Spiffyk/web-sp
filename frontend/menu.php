<?php
{
    if (Session::getInstance()->isActive()) {
        include __DIR__ . "/menu.logged.php";
    } else {
        include __DIR__ . "/menu.anonymous.php";
    }
}
