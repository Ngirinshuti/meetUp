<?php 

require_once __DIR__ . "/./comment_api.php";

if (!isset($_POST['comment_id'])) {
    $closure = fn () => ["errors" => ["comment_id" => ["Comment necessary"]]];
    return sendJson($closure);
}

$comment_id = intval($_POST['comment_id']);
$comment = Comment::findOne($comment_id);

if ($comment->likedBy($me->username)){
    $comment->unlike($me->username);
} else {
    $comment->like($me->username);
}

return sendJson(fn() => Comment::findOne($comment_id));

