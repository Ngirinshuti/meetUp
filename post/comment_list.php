<?php 

require_once __DIR__ . "/./comment_api.php";

if (!isset($_GET['post_id'])) {
    $closure = fn () => ["errors" => ["post_id" => ["Post is necessary"]]];
    return sendJson($closure);
}

$post_id = intval($_GET['post_id']);

$comments = Comment::findByPost($post_id);
$comment_paginator = new Paginator();
$comment_paginator->updateHasMore(count($comments));


$prev_url = $comment_paginator->hasPrev() ? (
    current_url_full() . "&page=" . ($comment_paginator->getCurrentPage() - 1)
) : null;

$next_url = $comment_paginator->has_more ? (
    current_url_full() . "&page=". ($comment_paginator->getCurrentPage() + 1)
) : null;

$data = [
    "data" => $comments,
    "next_url" => $next_url,
    "prev_url" => $prev_url
];

return sendJson(fn() => $data);


