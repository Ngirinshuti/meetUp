<?php

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    exit("{$_REQUEST['REQUEST_METHOD']} Method not allowed");
}

require "./Story.php";

$reply = $_POST['reply'];
$sender = $_POST['sender'];
$story_id = (int)$_POST['story_id'];

$story = Story::findOne($story_id);

$replied = $story->reply($sender, $reply);

if ($replied) {
    header("location:./index.php?story={$story->id}");
} else {
    exit("Replying failed...");
}


?>
