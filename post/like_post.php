<?php 

require_once __DIR__ . "/../classes/init.php";

if (!isset($_POST['username']) || !isset($_POST['post_id'])) {
    header("Location: ./home.php");
    exit;
}

require_once "./Post.php";

$post = Post::findOne((int) $_POST['post_id']);

if ($post->likedBy($me->username)) {
    $post->unlike($me->username);
} else {
    $post->like($me->username);
}

$back_url = isset($_REQUEST['back_url']) ? $_REQUEST['back_url'] : null;

if ($back_url === null) {
    header("Location: ./home.php#post".$_POST['post_id']);
}

header("Location: $back_url#post".$_POST['post_id']);


