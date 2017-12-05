<?php
    if (!empty($_POST["action"]) && $_POST["action"] == "register") {
        require_once __DIR__."/register/register.php";
    } else {
        require_once __DIR__."/register/form.php";
    }
?>