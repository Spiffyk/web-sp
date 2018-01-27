<?php
$session = Session::getInstance();
$messenger = Messenger::getInstance();

$session->logout();
$messenger->message(Messenger::TYPE_INFO, "Uživatel byl odhlášen.");
