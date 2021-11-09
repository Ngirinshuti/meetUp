<?php 


require_once  __DIR__ . '/../classes/init.php';
require_once __DIR__ . "/./Comment.php";

function sendJson(Closure $callback) {
    header("Content-Type: application/json");
    exit(json_encode($callback()));
}

