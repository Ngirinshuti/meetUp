<?php 
// authenticate file
session_start();

// require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/Auth.php";

if (($me = Auth::currentUser())) {
    header("Location: $ROOT_URL/index.php");
    exit();

} elseif (($me = Auth::checkRemembered())) {
    Auth::authenticate($me);
    header("Location: $ROOT_URL/index.php");
    exit();
}

