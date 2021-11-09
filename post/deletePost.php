<?php
// home feed file

require_once __DIR__ . "/../classes/init.php";

require_once __DIR__ . "/../post/Post.php";
$pos=new Post;
$posts = $pos->delete($_GET['id']);

?>
         