<?php
// home feed file

require_once __DIR__ . "/../classes/init.php";

require_once __DIR__ . "/../post/Post.php";


if (!isset($_GET['id'])) {
    header("Location: " . getBackUrl());
}


$post = Post::findOne($_GET['id']);

if (file_exists(toDir("/post/videos/{$post->video}"))) {
    unlink(toDir("/post/videos/{$post->video}"));
}

if (file_exists(toDir("/post/images/{$post->image}"))) {
    unlink(toDir("/post/images/{$post->image}"));
}


$post = $post->delete($_GET['id']);

?>
         