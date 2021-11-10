<?php
session_start();
// authenticate file

// require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/Auth.php";


if (!($me = Auth::currentUser())) {
    $me = Auth::checkRemembered();

    if (!$me) {
        header("Location: $ROOT_URL/auth/index.php");
        exit("Unauthenticated");
    } 
    
    Auth::authenticate($me);
}
