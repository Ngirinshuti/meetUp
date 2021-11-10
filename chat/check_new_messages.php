<?php

require_once __DIR__ . '/../classes/init.php';
require_once __DIR__ . '/Message.php';


if (!isset($_GET['last_message_date'])) {
    sendJson(fn() => "Last message date not provided");
}

$sender = isset($_GET['sender']) ? $_GET['sender'] : null;
$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : null;

if ($group_id) {
    $sender = null;
}

$messages = Message::checkNewMesssages(
    $me->username, $_GET['last_message_date'], $sender ?: null, $group_id ?: null
);

foreach ($messages as $msg) {
    $msg->read();
}

sendJson(fn () => $messages);


function sendJson(Closure $callback)
{
    header("Content-Type: application/json", true, 200);
    exit(json_encode($callback()));
}

