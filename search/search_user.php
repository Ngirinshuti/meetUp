<?php 

require_once  __DIR__ . '/../classes/init.php';

function sendJson(Closure $callback) {
    header("Content-Type: application/json");
    exit(json_encode($callback()));
}

$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

if (empty($search)) {
    sendJson(fn() => []);
}

sendJson(fn() => User::search($search));
